<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductsFilter;
use App\Models\ProductsAttribute;
use App\Models\Cart;
use App\Models\User;
use App\Models\Coupon;
use App\Models\DeliveryAddress;
use App\Models\Country;
use App\Models\Order;
use App\Models\OrdersProduct;
use Illuminate\Support\Facades\Mail;
use Session;
use DB;
use Auth;

class ProductController extends Controller
{
    public function listing(Request $request){
        $url = Route::getFacadeRoot()->current()->uri;
        $categoryCount = Category::where(['url' => $url,'status' => 1])->count();
        if($categoryCount > 0) {

            // get category details
            $categoryDetails = Category::categoryDetails($url);

            // get category and their sub category products
            $categoryProducts = Product::with(['brand','images'])->whereIn('category_id',$categoryDetails['catIds'])->where('products.status',1);
            // dd($categoryProducts);

            if(isset($request['sort']) && !empty($request['sort'])){
                if($request['sort'] == "product_latest"){
                    $categoryProducts->orderBy('id','Desc');
                } else if ($request['sort'] == "lowest_price"){
                    $categoryProducts->orderBy('final_price','ASC');
                } else if($request['sort'] == "highest_price"){
                    $categoryProducts->orderBy('final_price','DESC');
                } else if($request['sort'] == "best_selling"){
                    $categoryProducts->where('is_bestseller','Yes');
                } else if($request['sort'] == "featured_items"){
                    $categoryProducts->where('is_featured','Yes');
                } else if($request['sort'] == "discounted_items"){
                    $categoryProducts->where('product_discount','>',0);
                } else {
                    $categoryProducts->orderBy('products.id','Desc');
                }
            }


            // update query for colors filter
            if(isset($request['color']) && !empty($request['color'])){
                $colors = explode('~', $request['color']);
                $categoryProducts->whereIn('products.family_color',$colors);
            }

            // update query for sizes filter
            if(isset($request['size']) && !empty($request['size'])){
                $sizes = explode('~', $request['size']);
                $categoryProducts->join('products_attributes','products_attributes.product_id','=','products.id')->whereIn('products_attributes.size',$sizes)->groupBy('products_attributes.product_id');
            }

            // update query for brands filter
            if(isset($request['brand']) && !empty($request['brand'])){
                $brands = explode('~',$request['brand']);
                $categoryProducts->whereIn('products.brand_id',$brands);
            }

            // update query for price filter
            if(isset($request['price']) && !empty($request['price'])){
                $request['price'] = str_replace("~","-", $request['price']);
                $prices = explode('-',$request['price']);
                $count = count($prices);
                $categoryProducts->whereBetween('products.final_price', [$prices[0], $prices[$count-1]]);
            }

            // update query for dynamic filters
            $filterTypes = ProductsFilter::filterTypes();
            foreach ($filterTypes as $key => $filter){
                if($request->$filter){
                    $explodeFiltervals = explode('~', $request->$filter);
                    $categoryProducts->whereIn($filter, $explodeFiltervals);
                }
            }

            $categoryProducts = $categoryProducts->paginate(30);

            if($request->ajax()){
                return response()->json([
                    'view' => (String) View::make('front.products.ajax_products_listing')->with(
                        compact('categoryDetails','categoryProducts','url'))
                ]);

            } else {
                return view('front.products.listing')->with(compact('categoryDetails','categoryProducts','url'));
            }

        } else if(isset($_GET['query']) && !empty($_GET['query'])) {
            $search = $_GET['query'];

            // get search query products
            $categoryProducts = Product::with(['brand','images'])->where(function($query)use($search){
                $query->where('product_name','like','%'.$search.'%')
                ->orWhere('product_code','like','%'.$search.'%')
                ->orWhere('product_color','like','%'.$search.'%')
                ->orWhere('description','like','%'.$search.'%');
            })->where('status',1);
            $categoryProducts = $categoryProducts->get();

            return view('front.products.listing')->with(compact('categoryProducts'));
        } else {
            abort(404);
        }
    }

    public function detail($id){
        $productCount = Product::where('status', 1)->where('id',$id)->count();
        if($productCount == 0) {
            abort(404);
        }
        $productDetails = Product::with(['category','brand','attributes' => function($query){
            $query->where('stock','>',0)->where('status',1);
        },'images'])->find($id)->toArray();
        // get category details
        $categoryDetails = Category::categoryDetails($productDetails['category']['url']);
        // get group products (product colors)
        $groupProducts = array();
        if(!empty($productDetails['group_code'])){
            $groupProducts = Product::select('id','product_color')->where('id','!=',$id)->where([
                'group_code' => $productDetails['group_code'],
                'status' =>1
                ])->get()->toArray();
                // dd($groupProducts);
        }

        // get related products
        $relatedProducts = Product::with('brand','images')->where('category_id',$productDetails['category']['id'])->where('id','!=',$id)->limit(4)->inRandomOrder()->get()->toArray();

        // set session for recently viewed items
        if (empty(Session::get('session_id'))){
            $session_id = md5(uniqid(rand(),true));
        } else {
            $session_id = Session::get('session_id');
        }
        Session::put('session_id',$session_id);

        // insert product in recently_viewed_items table if not already exists
        $countRecentlyViewedItems = DB::table('recently_viewed_items')->where([
            'product_id' => $id,
            'session_id' => $session_id
        ])->count();
        if($countRecentlyViewedItems == 0){
            DB::table('recently_viewed_items')->insert([
                'product_id' => $id,
                'session_id' => $session_id
            ]);
        }

        // get recently viewed products ids
        $recentProductIds = DB::table('recently_viewed_items')->select('product_id')->where('product_id','!=',$id)->where('session_id',$session_id)->inRandomOrder()->get()->take(4)->pluck('product_id');

        // get recently viewed products
        $recentlyViewedProducts = Product::with('brand','images')->whereIn('id',$recentProductIds)->where('id','!=',$id)->get()->toArray();

        return view('front.products.detail')->with(compact('productDetails','categoryDetails', 'groupProducts','relatedProducts','recentlyViewedProducts'));
    }

    public function getAttributePrice(Request $request){
        if($request->ajax()){
            $data = $request->all();

            $getAttributePrice = Product::getAttributePrice($data['product_id'], $data['size']);
            return $getAttributePrice;
        }
    }

    public function addToCart(Request $request){
        if($request->isMethod('post')){
            $data = $request->all();

            // forget the coupon sessions
            Session::forget('couponAmount');
            Session::forget('couponCode');

            // check product stock
            $productStock = ProductsAttribute::productStock($data['product_id'], $data['size']);
            if ($data['qty'] > $productStock) {
                $message = "Required Quantity is not available!";
                return response()->json([
                    'status' => false,
                    'message' => $message]);
            }

            // check product status
            $productStatus = Product::productStatus($data['product_id']);
            if ($productStatus == 0) {
                $message = "Product is not available!";
                return response()->json([
                    'status' => false,
                    'message' => $message]);
            }

            // generate session id if not exists
            if(empty(Session::get('session_id'))){
                $session_id = Session::getId();
                Session::put('session_id', $session_id);
            } else {
                $session_id = Session::get('session_id');
            }

            // check product if already exists in the user cart
            if(Auth::Check()){
                // user is logged in
                $user_id = Auth::user()->id;
                $countProducts = Cart::where([
                    'product_id' => $data['product_id'],
                    'product_size' => $data['size'],
                    'user_id' => $user_id])->count();
            } else {
                // user is not logged in
                $user_id = 0;
                $countProducts = Cart::where([
                    'product_id' => $data['product_id'],
                    'product_size' => $data['size'],
                    'session_id' => $session_id])->count();

            }

            if($countProducts > 0) {
                $message = "Product already exists in Cart";
                return response()->json([
                    'status' => false,
                    'message' => $message
                ]);
            }

            // save the product in carts table
            $item = new Cart;
            $item->session_id = $session_id;
            if(Auth::check()) {
                $item->user_id = Auth::user()->id;
            }
            $item->product_id = $data['product_id'];
            $item->product_size = $data['size'];
            $item->product_qty = $data['qty'];
            $item->save();

            // get total cart items
            $totalCartItems = totalCartItems();
            $getCartItems = getCartItems();

            $message = "Product added successfully in Cart! <a style='color:#ffffff; text-decoration:underline;' href='/cart'>View Cart</a>";
            return response()->json([
               'status' => true,
               'message' => $message,
               'totalCartItems' => $totalCartItems,
               'minicartview' => (String)View::make('front.layout.header_cart_items')->with(compact('getCartItems'))
            ]);
        }
    }

    public function cart() {
        $getCartItems = getCartItems();
        return view('front.products.cart')->with(compact('getCartItems'));
    }

    public function updateCartItemQty(Request $request){
        if($request->ajax()){
            $data = $request->all();

            // forget the coupon sessions
            Session::forget('couponAmount');
            Session::forget('couponCode');

            // get cart details
            $cartDetails = Cart::find($data['cartid']);

            $availableStock = ProductsAttribute::select('stock')->where([
                'product_id' => $cartDetails['product_id'],
                'size' => $cartDetails['product_size']])->first()->toArray();

                // check if desired stock from user is available
                if(($data['qty'] > $availableStock['stock'])){
                    $getCartItems = getCartItems();
                    return response()->json([
                        'status' => false,
                        'message' => 'Product Stock is not available',
                        'view' => (String)View::make('front.products.cart_items')->with(compact('getCartItems')),
                        'minicartview' => (String)View::make('front.layout.header_cart_items')->with(compact('getCartItems'))
                    ]);
                }

                // check if product size is available
                $availableSize = ProductsAttribute::where([
                    'product_id' => $cartDetails['product_id'],
                    'size' => $cartDetails['product_size'],
                    'status' => 1
                ])->count();

                if($availableSize == 0) {
                    $getCartItems = getCartItems();
                    return response()->json([
                        'status' => false,
                        'message' => 'Product Size is not available. Please remove and choose another one!',
                        'view' => (String)View::make('front.products.cart_items')->with(compact('getCartItems')),
                        'minicartview' => (String)View::make('front.layout.header_cart_items')->with(compact('getCartItems'))
                    ]);
                }


            // update the cart item qty
            Cart::where('id',$data['cartid'])->update(['product_qty' => $data['qty']]);

            // get updated cart items
            $getCartItems = getCartItems();

            // get total cart items
            $totalCartItems = totalCartItems();

            return response()->json([
                'status' => true,
                'totalCartItems' => $totalCartItems,
                'view' => (String)View::make('front.products.cart_items')->with(compact('getCartItems')),
                'minicartview' => (String)View::make('front.layout.header_cart_items')->with(compact('getCartItems'))
            ]);
        }
    }

    public function deleteCartItem(Request $request) {
        if($request->ajax()) {
            $data = $request->all();

            // forget the coupon sessions
            Session::forget('couponAmount');
            Session::forget('couponCode');

            Cart::where('id',$data['cartid'])->delete();

            // get updated cart items
            $getCartItems = getCartItems();

            $totalCartItems = totalCartItems();

            return response()->json([
               'status' => true,
               'totalCartItems' => $totalCartItems,
                'view' => (String)View::make('front.products.cart_items')->with(compact('getCartItems')),
                'minicartview' => (String)View::make('front.layout.header_cart_items')->with(compact('getCartItems'))
            ]);
        }
    }


    public function emptyCart(Request $request) {
        if($request->ajax()) {

            emptyCart();

            // get updated cart items
            $getCartItems = getCartItems();

            $totalCartItems = totalCartItems();

            return response()->json([
               'status' => true,
               'totalCartItems' => $totalCartItems,
                'view' => (String)View::make('front.products.cart_items')->with(compact('getCartItems')),
                'minicartview' => (String)View::make('front.layout.header_cart_items')->with(compact('getCartItems'))
            ]);
        }
    }

    public function applyCoupon(Request $request){
        if($request->ajax()){
            $data = $request->all();

            // get update cart items
            $getCartItems = getCartItems();

            // get total cart items
            $totalCartItems = totalCartItems();

            // verify coupon is valid or not
            $couponCount = Coupon::where('coupon_code',$data['code'])->count();
            if($couponCount==0){
                return response()->json([
                    'status' => false,
                    'totalCartItems' => $totalCartItems,
                    'message' => 'The coupon is not valid!',
                     'view' => (String)View::make('front.products.cart_items')->with(compact('getCartItems')),
                     'minicartview' => (String)View::make('front.layout.header_cart_items')->with(compact('getCartItems'))
                 ]);
            } else {
                //get coupon details
                $couponDetails = Coupon::where('coupon_code',$data['code'])->first();

                // if coupon is inactive
                if($couponDetails->status==0){
                    $error_message = "The coupon is not active!";
                }

                 // if coupon is expired
                $expiry_date = $couponDetails->expiry_date;
                $current_date = date('Y-m-d');
                if($expiry_date < $current_date){
                 $error_message = "The coupon is expired!";
                }

                // check if coupon is for Single or Multiple Times
                if($couponDetails->coupon_type == "Single Time"){
                    // check in orders if coupon is already availed by the user
                    $couponCount = Order::where([
                        'coupon_code' => $data['code'],
                        'user_id' => Auth::user()->id
                    ])->count();
                    if($couponCount >= 1) {
                        $error_message = "This coupon code is already availed by you!";
                    }
                }


                // get all selected categories from coupon
                $catArr = explode(",",$couponDetails->categories);

                // get all selected brands from coupon
                $brandsArr = explode(",",$couponDetails->brands);

                // get all selected users from coupon
                $usersArr = explode(",",$couponDetails->users);

                foreach($usersArr as $key => $user){
                    $getUserID = User::select('id')->where('email',$user)->first()->toArray();
                    $usersID[] = $getUserID['id'];
                }

                // check if any cart item dose not belong to coupon category, brand, and user
                $total_amount = 0;
                foreach($getCartItems as $key => $item) {
                    // check if any cart item dose not belong to coupon category
                    if(!in_array($item['product']['category_id'], $catArr)){
                        $error_message = "The coupon code is not for one of the selected products.";
                    }
                    // check if any cart item dose not belong to coupon brand
                    if(!in_array($item['product']['brand_id'], $brandsArr)){
                        $error_message = "The coupon code is not for one of the selected products.";
                    }
                    // check if any cart item dose not belong to coupon user
                    if(count($usersArr)>0) {
                        if(!in_array($item['user_id'],$usersID)){
                            $error_message = "The coupon code is not for you. Try again with valid coupon code!";
                        }
                    }
                    $getAttributePrice = Product::getAttributePrice($item['product_id'], $item['product_size']);
                    $total_amount = $total_amount + ($getAttributePrice['final_price'] * $item['product_qty']);
                }

                // if error message is there
                if(isset($error_message)){
                    return response()->json([
                        'status' => false,
                        'totalCartItems' => $totalCartItems,
                        'message' => $error_message,
                         'view' => (String)View::make('front.products.cart_items')->with(compact('getCartItems')),
                         'minicartview' => (String)View::make('front.layout.header_cart_items')->with(compact('getCartItems'))
                     ]);
                } else {
                    // apply coupon if coupon code is correct

                    // check if coupon amount type is fixed or percentage
                    if($couponDetails->amount_type=="Fixed"){
                        $couponAmount = $couponDetails->amount;
                    } else {
                        $couponAmount = $total_amount * ($couponDetails->amount/100);
                    }

                    $grand_total = $total_amount - $couponAmount;

                    // Add cpupon code & amount in session variables
                    Session::put('couponAmount', $couponAmount);
                    Session::put('couponCode', $data['code']);

                    $message = "Coupon Code successfully applied. you are availing discount!";

                    return response()->json([
                        'status' => true,
                        'totalCartItems' => $totalCartItems,
                        'couponAmount' => $couponAmount,
                        'grandTotal' => $grand_total,
                        'message' => $message,
                         'view' => (String)View::make('front.products.cart_items')->with(compact('getCartItems')),
                         'minicartview' => (String)View::make('front.layout.header_cart_items')->with(compact('getCartItems'))
                     ]);
                }
            }
        }
    }

    public function checkout(Request $request){

        // Get updated cart items
        $getCartItems = getCartItems();
        if(count($getCartItems) == 0) {
            $message = "Shopping Cart is empty! Please add produts to Checkout!";
            return redirect('cart')->with('error_message',$message);
        }



        if($request->isMethod('post')){
            $data = $request->all();

        // check for delivery address
        $deliveryAddressCount = DeliveryAddress::where('user_id',Auth::user()->id)->count();

        if($deliveryAddressCount==0) {
            return redirect()->back()->with('error_message','Please add your Delivery Address');
        }
        // check for payment method
        if(empty($data['payment_gateway'])) {
            return redirect()->back()->with('error_message','Please select Payment Method');
        }

        if(!isset($data['agree'])) {
            return redirect()->back()->with('error_message','Please agree to T&C!');
        }

        $deliveryAddressDefaultCount = DeliveryAddress::where('user_id',Auth::user()->id)->where('is_default',1)->count();
        if($deliveryAddressDefaultCount==0) {
            return redirect()->back()->with('error_message','Please select your Delivery Address');
        } else {
            $deliveryAddress = DeliveryAddress::where('user_id',Auth::user()->id)->where('is_default',1)->first()->toArray();
        }

        // Set payment method as COD and Order Status as New if COD is seleted from user otherwise set Payment Method as Prepaid and order status as pending

        if($data['payment_gateway']=="COD"){
            $payment_method = "COD";
            $order_status = "New";
        } else if($data['payment_gateway']=="Bank Transfer"){
            $payment_method = "Prepaid";
            $order_status = "Pending";
        } else if($data['payment_gateway']=="Check"){
            $payment_method = "Prepaid";
            $order_status = "Pending";
        } else {
            $payment_method = "Prepaid";
            $order_status = "Pending";
        }
        DB::beginTransaction();

        // fetch order total price
        $total_price = 0;
        foreach($getCartItems as $item) {
            $getAttributePrice = Product::getAttributePrice($item['product_id'],$item['product_size']);
            $total_price = $total_price + ($getAttributePrice['final_price'] * $item['product_qty']);
        }

        // get shipping charges
        $shipping_charges = 0;

        // calculate grand total
        $grand_total = $total_price + $shipping_charges - Session::get('couponAmount');

        // insert grand total in session variable
        Session::put('grand_total',$grand_total);

        // insert order details
        $order = new Order;
        $order->user_id = Auth::user()->id;
        $order->name = $deliveryAddress['name'];
        $order->address = $deliveryAddress['address'];
        $order->city = $deliveryAddress['city'];
        $order->state = $deliveryAddress['state'];
        $order->country = $deliveryAddress['country'];
        $order->pincode = $deliveryAddress['pincode'];
        $order->mobile = $deliveryAddress['mobile'];
        $order->shipping_charges = $shipping_charges;
        $order->coupon_code = Session::get('couponCode');
        $order->coupon_amount = Session::get('couponAmount');
        $order->order_status = $order_status;
        $order->payment_method = $payment_method;
        $order->payment_gateway = $data['payment_gateway'];
        $order->grand_total = $grand_total;
        $order->save();
        $order_id = DB::getPdo()->lastInsertId();

        foreach($getCartItems as $key => $item) {
            $getProductDetails = Product::getProductDetails($item['product_id']);
            $getAttributeDetails = Product::getAttributeDetails(
                $item['product_id'],
                $item['product_size']);
            $getAttributePrice = Product::getAttributePrice(
                $item['product_id'],
                $item['product_size']);

            $cartItem = new OrdersProduct;
            $cartItem->order_id = $order_id;
            $cartItem->user_id = Auth::user()->id;
            $cartItem->product_id = $item['product_id'];
            $cartItem->product_code = $getProductDetails['product_code'];
            $cartItem->product_name = $getProductDetails['product_name'];
            $cartItem->product_color = $getProductDetails['product_color'];
            $cartItem->product_size = $item['product_size'];
            $cartItem->product_sku = $getAttributeDetails['sku'];
            $cartItem->product_price = $getAttributePrice['final_price'];
            $cartItem->product_qty = $item['product_qty'];
            $cartItem->save();

            if($data['payment_gateway'] == "COD" || $data['payment_gateway']=="Bank Transfer" || $data['payment_gateway']=="Check") {
            // Reduce Stock Scripts Start
            $getProductStock = ProductsAttribute::productStock(
                $item['product_id'],
                $item['product_size']
            );
            $newStock = $getProductStock - $item['product_qty'];
            ProductsAttribute::where([
                'product_id' => $item['product_id'],
                'size' => $item['product_size']
            ])->update([
                'stock' => $newStock]);
            }
        }

        // insert order id in session variable
        Session::Put('order_id',$order_id);

        DB::commit();

        if($data['payment_gateway'] == "COD" || $data['payment_gateway']=="Bank Transfer" || $data['payment_gateway']=="Check") {

            // get user detail
            $orderDetails = Order::with('orders_products','user')->where('id',$order_id)->first()->toArray();

            // send order email
            $email = Auth::user()->email;
            $messageData = [
                'email' => $email,
                'name' => Auth::user()->name,
                'order_id' => $order_id,
                'orderDetails' => $orderDetails
            ];
            Mail::send('emails.order',$messageData, function($message) use($email){
                $message->to($email)->subject('Order Placed - SiteMakers');
            });
            if($data['payment_gateway'] == "COD") {
                return redirect('/thanks');
            } else if($data['payment_gateway']=="Bank Transfer"){
                return redirect('/thanks?order=bank');
            } else if($data['payment_gateway']=="Check"){
                return redirect('/thanks?order=check');
            }

        } if($data['payment_gateway'] == "Paypal") {
            // paypal - redirect user to paypal page after saving order
            return redirect('paypal');
            echo "Prepaid methods coming soon"; die();
        }


    }

        // get user delivery addresses
        $deliveryAddresses = DeliveryAddress::deliveryAddresses();

        // get all countries
        $countries = Country::where('status',1)->get()->toArray();
        return view('front.products.checkout')->with(compact('getCartItems','deliveryAddresses','countries'));
    }

    public function thanks() {
        if(Session::has('order_id')){
            // empty the user cart
            Cart::where('user_id',Auth::user()->id)->delete();
            return view('front.orders.thanks');
        } else {
            return redirect('/cart');
        }
    }
}
