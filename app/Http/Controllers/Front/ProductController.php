<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Category;
use App\Models\Product;

class ProductController extends Controller
{
    public function listing(){
        $url = Route::getFacadeRoot()->current()->uri;
        $categoryCount = Category::where(['url' => $url,'status' => 1])->count();
        if($categoryCount > 0) {

            // get category details
            $categoryDetails = Category::categoryDetails($url);

            // get category and their sub category products
            $categoryProducts = Product::with(['brand','images'])->whereIn('category_id',$categoryDetails['catIds'])->where('status',1)->orderBy('id','Desc');
            // dd($categoryProducts);

            if(isset($_GET['sort']) && !empty($_GET['sort'])){
                if($_GET['sort'] == "product_latest"){
                    $categoryProducts->orderBy('id','Desc');
                } else if ($_GET['sort'] == "lowest_price"){
                    $categoryProducts->orderBy('final_price','ASC');
                } else if($_GET['sort'] == "highest_price"){
                    $categoryProducts->orderBy('final_price','DESC');
                } else if($_GET['sort'] == "best_selling"){
                    $categoryProducts->where('is_bestseller','Yes');
                } else if($_GET['sort'] == "featured_items"){
                    $categoryProducts->where('is_featured','Yes');
                } else if($_GET['sort'] == "discounted_items"){
                    $categoryProducts->where('product_discount','>',0);
                } else {
                    $categoryProducts->orderBy('id','Desc');
                }
            }

            $categoryProducts = $categoryProducts->paginate(2);

            return view('front.products.listing')->with(compact('categoryDetails','categoryProducts','url'));
        } {
            abort(404);
        }
    }
}
