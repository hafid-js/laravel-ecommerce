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
            $user->status = 0;
            $user->save();

            // activate the user only when user confirm his email account

            // send confirmation email
            $email = $data['email'];
            $messageData = [
                'name' => $data['name'],
                'email' => $data['email'],
                'code' => base64_encode($data['email'])];


                Mail::send('emails.confirmation', $messageData, function($message) use ($email) {
                $message->to($email)->subject('Confirm your Lektumbas.id Account');
            });

            // redirect back user with a success message
            $redirectURL = url('user/register');
            return response()->json([
               'status' => true,
                'type' =>'success',
               'redirectUrl' => $redirectURL,
            'message' => 'Please confirm your email to activate your account']);

            // if(Auth::attempt([
            //     'email' => $data['email'],
            //     'password' => $data['password']
            // ])){

            //     // send register email
            //     $email = $data['email'];
            //     $messageData = [
            //         'name' => $data['name'],
            //         'mobile' => $data['mobile'],
            //         'email' => $data['email']];

            //         Mail::send('emails.register', $messageData, function($message) use ($email) {
            //             $message->to($email)->subject('Welcome to Lektumbas.id');
            //         });

            //     $redirectUrl = url('cart');
            //     return response()->json([
            //         'status' => false,
            //         'type' => 'success',
            //         'redirectUrl' => $redirectUrl]);
            // }
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

    public function confirmAccount($code) {
        $email = base64_decode($code);
        $userCount = User::where('email', $email)->count();
        if($userCount > 0) {
            $userDetails = User::where('email', $email)->first();
            if($userDetails->status == 1) {
                // redirect the user to login page with the error message
                return redirect('user/login')->with('error_message','Your account is already activated. You can login now.');
            } else {
                User::where('email', $email)->update(['status' => 1]);

                // send welcome email
                $messageData = [
                    'name' => $userDetails->name,
                    'mobile' => $userDetails->mobile,
                    'email' => $email
                ];
                Mail::send('emails.register', $messageData, function($message) use($email){
                    $message->to($email)->subject('Welcome to Lektumbas.id');
                });

                // redirect the user to login page with the success message
                return redirect('user/login')->with('success_message','Your account is already activated. You can login now.');
            }
        } else {
            abort(404);
        }
    }


    public function userLogout(){
        Auth::logout();
        return redirect('user/login');
    }
}
