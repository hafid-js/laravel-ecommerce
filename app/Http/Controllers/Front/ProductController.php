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
        } {
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

        echo "Proceed to Place Order";
        }

        // get user delivery addresses
        $deliveryAddresses = DeliveryAddress::deliveryAddresses();

        // get all countries
        $countries = Country::where('status',1)->get()->toArray();
        return view('front.products.checkout')->with(compact('getCartItems','deliveryAddresses','countries'));
    }
}
