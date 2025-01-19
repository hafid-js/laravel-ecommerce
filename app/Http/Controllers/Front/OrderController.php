<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Auth;

class OrderController extends Controller
{
    public function orders() {
        $orders = Order::with('orders_products')->where('user_id',Auth::user()->id)->orderBy('id','DESC')->get();
        return view('front.orders.orders')->with(compact('orders'));
    }

    public function orderDetails($id) {
        $orderDetails = Order::with('orders_products','user','log')->where('id',$id)->first()->toArray();
        return view('front.orders.order_detail')->with(compact('orderDetails'));
    }


}
