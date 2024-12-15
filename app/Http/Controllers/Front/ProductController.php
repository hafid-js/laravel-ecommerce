<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use App\Models\Category;
use App\Models\Product;

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

            $categoryProducts = $categoryProducts->paginate(6);

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
}
