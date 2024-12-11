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
        $newProducts = Product::with(['brand','images'])->where('status',1)->orderBy('id','Desc')->limit(4)->get()->toArray();

            return view('front.index')->with(compact('homeSliderBanners','homeFixBanners','newProducts'));
        }
}
