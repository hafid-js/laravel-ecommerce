<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Validator;
use Hash;
use App\Models\Admin;
use Image;

class AdminController extends Controller
{
    public function dashboard() {
        return view('admin.layout.dashboard');
    }

    public function login(Request $request) {
        if($request->isMethod('post')) {
            $data = $request->all();
            // echo "<pre></pre>"; print_r($data);


            $rules = [
                'email' => 'required|email|max:255',
                'password' => 'required|max:30'
            ];

            $customMessages = [
                'email.required' => 'Email is required',
                'email.email' => 'Valid Email is required',
                'password.required' => 'Password is required',
            ];

            $this->validate($request, $rules, $customMessages);

            if(Auth::guard('admin')->attempt(
                ['email' => $data['email'],
                'password' => $data['password']])) {
                    return redirect("admin/dashboard");
                } else {
                    return redirect()->back()->with("error_message", "Invalid Email or Password!");
                }
        }
        return view('admin.login');
    }

    public function logout() {
        Auth::guard('admin')->logout();
        return redirect('admin/login');
    }

    public function updatePassword(Request $request) {
        if ($request->isMethod('post')) {
            $data = $request->all();
            if(Hash::check($data['current_pwd'], Auth::guard('admin')->user()->password)){
                if($data['new_pwd'] == $data['confirm_pwd']) {
                    Admin::where('id', Auth::guard('admin')->user()->id)->update(['password' => bcrypt($data['new_pwd'])]);
                    return redirect()->back()->with('success_message', 'Password has been updated Successfully');
                } else {
                    return redirect()->back()->with('error_message', 'New Password and Retype Password is not match');
                }
            } else {
                return redirect()->back()->with('error_message','Your current password is Incorrect!');
            }
        }
        return view('admin.update_password');
    }

    public function checkCurrentPassword(Request $request) {
        $data = $request->all();
        if (Hash::check($data['current_pwd'], Auth::guard('admin')->user()->password)) {
            return "true";
        } else {
            return "false";
        }
    }

    public function updateDetails(Request $request) {
        if($request->isMethod('post')) {
            $data = $request->all();
            // echo "<pre></pre>"; print_r($data);


            $rules = [
                'admin_name' => 'required|regex:/^[\pL\s\-]+$/u|max:255',
                'admin_mobile' => 'required|numeric|digits:12',
                'admin_image' => 'image'
            ];

            $customMessages = [
                'admin_name.required' => 'Name is required',
                'admin_name.regex' => 'Valid Name is required',
                'admin_name.max' => 'Valid Name is required',
                'admin_mobile.required' => 'Mobile is required',
                'admin_mobile.numeric' => 'Valid Mobile is required',
                'admin_mobile.digits' => 'Valid Mobile is required',
                'admin_mobile.image' => 'Valid Image is required',
            ];

            $this->validate($request, $rules, $customMessages);

            if ($request->hasFile('admin_image')) {
                $image_tmp = $request->file('admin_image');
                if($image_tmp->isValid()) {
                    $extension = $image_tmp->getClientOriginalExtension();
                    $imageName = rand(111,99999).'.'.$extension;
                    $image_path = public_path('admin/images/photos/'.$imageName);
                    Image::make($image_tmp)->save($image_path);
                }

            } else if (!empty($data['current_image'])) {
                $imageName = $data['current_image'];
            } else {
                $imageName ="";
            }

            Admin::where('email', Auth::guard('admin')->user()->email)->update([
                'name' => $data['admin_name'],
                'mobile' => $data['admin_mobile'],
                'image' => $imageName
            ]);
            return redirect()->back()->with('success_message','Admin Details as been updated Successfully');
            }
        return view('admin.update_details');
    }

}
