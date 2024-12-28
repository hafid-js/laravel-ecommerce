<?php

namespace App\Http\Controllers\Front;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function loginUser() {
        return view('front.users.login');
    }

    public function registerUser(){
        return view('front.users.register');
    }
}
