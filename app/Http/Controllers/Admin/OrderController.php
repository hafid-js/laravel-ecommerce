<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Session;

class OrderController extends Controller
{
    public function orders() {
        Session::put('page','orders');
    $orders = Order::with('orders_products','user')->orderBy('id','DESC')->get()->toArray();
    return view('admin.orders.orders')->with(compact('orders'));
    }

    public function orderDetails($id){
        $orderDetails = Order::with('orders_products','user')->where('id',$id)->first()->toArray();

        return view('admin.orders.order_detail')->with(compact('orderDetails'));
    }
}
