<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;
use Session;

class Cart extends Model
{
    use HasFactory;

    public static function getCartItems() {
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

    public function product() {
        return $this->belongsTo('App\Models\Product', 'product_id')->with('brand','images');
    }
}
