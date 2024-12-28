<?php

namespace App\Http\Controllers\Front;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Validator;
use Auth;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function loginUser() {
        return view('front.users.login');
    }

    public function registerUser(Request $request){
        if($request->ajax()) {

            $validator = Validator::make($request->all(),
            [
                'name' => 'required|string|max:150',
                'mobile' => 'required|numeric|min_digits:10|max_digits:12',
                'email' => 'required|email|max:250|unique:users',
                'password' => 'required|string|min:6'
            ],
        [
            'email.email' => 'Please enter the valid Email'
        ]);

        if($validator->passes()){

            $data = $request->all();
            // echo "<pre></pre>"; print_r($data); die();


            // register the user
            $user = new User();
            $user->name = $data['name'];
            $user->mobile = $data['mobile'];
            $user->email = $data['email'];
            $user->password = bcrypt($data['password']);
            $user->status = 1;
            $user->save();

            if(Auth::attempt([
                'email' => $data['email'],
                'password' => $data['password']
            ])){

                // send register email
                $email = $data['email'];
                $messageData = [
                    'name' => $data['name'],
                    'mobile' => $data['mobile'],
                    'email' => $data['email']];

                    Mail::send('emails.register', $messageData, function($message) use ($email) {
                        $message->to($email)->subject('Welcome to Lektumbas.id');
                    });

                $redirectUrl = url('cart');
                return response()->json([
                    'status' => false,
                    'type' => 'success',
                    'redirectUrl' => $redirectUrl]);
            }
        } else {
            return response()->json([
                'status' => false,
                'type' => 'validation',
                'errors' => $validator->messages()
            ]);
        }
    }
        return view('front.users.register');
    }

    public function userLogout(){
        Auth::logout();
        return redirect('user/login');
    }
}
