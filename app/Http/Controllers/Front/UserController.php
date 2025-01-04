<?php

namespace App\Http\Controllers\Front;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\Country;
use Validator;
use Auth;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function loginUser(Request $request) {
        if($request->ajax()) {
            $data = $request->all();
            $validator = Validator::make($request->all(),
            [
                'email' => 'required|email|max:250|exists:users',
                'password' => 'required|string|min:6'
            ],
        [
            'email.exists' => 'Email does not exists'
        ]);

        if($validator->passes()){
            if(Auth::attempt([
                'email' => $data['email'],
                'password' => $data['password']
            ])) {

                // remember user email and password
                if(!empty($data['remember-me'])){
                    setcookie("user-email", $data['email'], time()+3600);
                    setcookie("user-password", $data['password'], time()+3600);
                } else {
                    setcookie("user-email");
                    setcookie("user-password");
                }

                if(Auth::user()->status == 0) {
                    Auth::logout();
                    return response()->json([
                        'status' => false,
                        'type' => 'inactive',
                        'message' => 'Your account is not activated yet!'
                    ]);
                }


            $redirectUrl = url('cart');
            return response()->json([
               'status' => true,
                'type' => 'success',
               'redirectUrl' => $redirectUrl]);
            } else {
                return response()->json([
                    'status' => false,
                    'type' => 'incorrect',
                    'message' => 'You have entered wrong email or password'
                ]);
            }

        } else {
            return response()->json([
                'status' => false,
                'type' => 'error',
                'errors' => $validator->messages()
            ]);
            }
        }
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

    public function forgotPassword(Request $request){
        if($request->ajax()){
            $data = $request->all();

            $validator = Validator::make($request->all(),
            [
                'email' => 'required|email|max:250|exists:users',
            ],
        [
            'email.exists' => 'Email does not exists',
        ]);

        if($validator->passes()){

            // send email to user with reset password link
            $email = $data['email'];
            $messageData = [
                'email' => $data['email'],
                'code' => base64_encode($data['email'])
            ];

            Mail::send('emails.reset_password', $messageData, function($message) use ($email) {
                $message->to($email)->subject('Reset your Password - Lektumbas.id Account');
            });
            // show success message
            return response()->json([
            //    'status' => true,
                'type' =>'success',
                'message' => 'Reset Password link sent to your registered email.']);

        } else {
            return response()->json([
                'status' => false,
                'type' => 'validation',
                'errors' => $validator->messages()
            ]);
        }

        } else {
            return view('front.users.forgot_password');
        }
    }

    public function resetPassword(Request $request, $code=null){
        if($request->ajax()){
            $data = $request->all();

            $email = base64_decode($data['code']);
            $userCount = User::where('email',$email)->count();
            if($userCount > 0) {
                // update new password
                User::where('email', $email)->update(['password' => bcrypt($data['password'])]);

                // send confirmation email to user
                $messageData = ['email' => $email];
                Mail::send('emails.new_password_confirmation', $messageData, function($message) use ($email) {
                    $message->to($email)->subject('Password Updated - Lektumbas.id');
                });

                // show success message
                return response()->json([
                    'type' =>'success',
                   'message' => 'Password has been successfully updated. You can now login now']);

            } else {
                abort(404);
            }
        } else {
            return view('front.users.reset_password')->with(compact('code'));
        }
    }

    public function userLogout(){
        Auth::logout();
        return redirect('user/login');
    }

    public function account(Request $request) {
        if($request->ajax()) {
            $data = $request->all();
            $validator = Validator::make($request->all(),
            [
                'name' => 'required|string|max:150',
                'city' => 'required|string|max:150',
                'state' => 'required|string|max:150',
                'address' => 'required|string|max:150',
                'pincode' => 'required|string|max:150',
                'mobile' => 'required|numeric|min_digits:10|max_digits:12',
            ]);

        if($validator->passes()){
            // update user details
            User::where('id', Auth::user()->id)->update([
                'name' => $data['name'],
                'address' => $data['address'],
                'city' => $data['city'],
                'state' => $data['state'],
                'country' => $data['country'],
                'pincode' => $data['pincode'],
                'country' => $data['country'],
                'mobile' => $data['mobile'],
            ]);

            // redirect back user with success message
            return response()->json([
                'status' => true,
                'type' => 'success',
                'message' => 'User Details Successfully Updated!'
            ]);
        } else {
        return response()->json([
            'status' => false,
            'type' => 'validation',
            'errors' => $validator->messages()
        ]);
        }
    } else {
        $countries = Country::where('status',1)->get()->toArray();
        return view('front.users.account')->with(compact('countries'));
        }
    }
}
