<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;
use App\Models\Product;

class IndexController extends Controller
{
    public function index() {
        // get home page slider banners
        $homeSliderBanners = Banner::where('type','Slider')->where('status', 1)->orderBy('sort','ASC')->get()->toArray();

            // get home page fix banners
        $homeFixBanners = Banner::where('type','Fix')->where('status',1)->orderBy('sort','ASC')->get()->toArray();

        //get new arrival products
        $newProducts = Product::with(['brand','images'])->where('status',1)->orderBy('id','Desc')->limit(8)->get()->toArray();

        // get best seller products
        $bestSellers = Product::with(['brand','images'])->where(['is_bestseller' => 'Yes','status' => 1])->inRandomOrder()->limit(4)->get()->toArray();

        // get discounted products
        $discountedProducts = Product::with(['brand','images'])->where('product_discount','>',0)->where('status',1)->inRandomOrder()->limit(4)->get()->toArray();

        // get featured products
        $featuredProducts = Product::with(['brand','images'])->where('is_featured','Yes')->where('status',1)->inRandomOrder()->limit(8)->get()->toArray();

            return view('front.index')->with(compact('homeSliderBanners','homeFixBanners','newProducts','bestSellers','discountedProducts','featuredProducts'));
        }
}
