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
            $getCategoryDetails = Category::getCategoryDetails($url);

            // get category and their sub category products
            $categoryProducts = Product::with(['brand','images'])->whereIn('category_id',$getCategoryDetails['catIds'])->where('status',1)->orderBy('id','Desc')->get()->toArray();

            return view('front.products.listing')->with(compact('getCategoryDetails','categoryProducts'));
        } {
            abort(404);
        }
    }
}
