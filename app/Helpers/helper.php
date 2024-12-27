<?php

use App\Models\Cart;

function totalCartItems() {
    if(Auth::check()){
        $user_id = Auth::user()->id;
        $totalCartItems = Cart::where('user_id', $user_id)->sum('product_qty');
    } else {
        $session_id = Session::get('session_id');
        $totalCartItems = Cart::where('session_id', $session_id)->sum('product_qty');
    }
    return $totalCartItems;
}


function getCartItems() {
    if(Auth::Check()){
        // if the user is logged in, check from auth (user_id)
        $user_id = Auth::user()->id;
        $getCartItems = Cart::with('product')->where('user_id', $user_id)->get()->toArray();
    } else {
        // if the user is not logged in, check from session (session_id)
        $session_id = Session::get('session_id');
        $getCartItems = Cart::with('product')->where('session_id', $session_id)->get()->toArray();
    }
    return $getCartItems;
}
