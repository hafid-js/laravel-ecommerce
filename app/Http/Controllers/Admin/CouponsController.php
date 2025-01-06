<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Coupon;
use App\Models\AdminsRole;
use App\Models\Category;
use App\Models\Brand;
use App\Models\User;
use Session;
use Auth;
use Validator;

class CouponsController extends Controller
{
    public function coupons(){
        Session::put('page','coupons');
        $coupons = Coupon::get()->toArray();

        // set admin/subadmin permission for coupons
        $couponsModuleCount = AdminsRole::where(['subadmin_id' => Auth::guard('admin')->user()->id, 'module' => 'coupons'])->count();
        $couponsModule = array();
        if(Auth::guard('admin')->user()->type == "admin") {
            $couponsModule['view_access'] = 1;
            $couponsModule['edit_access'] = 1;
            $couponsModule['full_access'] = 1;
        } else if ($couponsModuleCount == 0) {
            $message = "This feature is restricted for you!";
            return redirect('admin/dashboard')->with('error_message', $message);
        } else {

            $couponsModule = AdminsRole::where(['subadmin_id' => Auth::guard('admin')->user()->id, 'module' => 'coupons'])->first()->toArray();
        }

        return view('admin.coupons.coupons')->with(compact('coupons','couponsModule'));
    }

    public function updateCouponStatus(Request $request)
    {
        if ($request->ajax()) {
            $data = $request->all();
            if($data['status'] == "Active") {
                $status = 0;
            } else {
                $status = 1;
            }
            Coupon::where('id', $data['coupon_id'])->update(['status' => $status]);
            return response()->json(['status' => $status,'coupon_id' => $data['coupon_id']]);
        }
    }

    public function deleteCoupon($id)
    {
        Coupon::where('id',$id)->delete();
        return redirect()->back()->with('success_message','Coupon deleted successfully');
    }

    public function addEditCoupon(Request $request, $id=null) {
        if($id==""){
            // add coupon
            $title = "Add Coupon";
            $coupon = new Coupon();
            $selCats = array();
            $selUsers = array();
            $selBrands = array();
            $message = "Coupon added successfully";
        } else {
            // edit coupon
            $title = "Edit Coupon";
            $coupon = Coupon::find($id);
            $selCats = explode(",",$coupon['categories']);
            $selUsers = explode(",",$coupon['users']);
            $selBrands = explode(",",$coupon['brands']);
            $message = "Coupon updated successfully";
        }

        if($request->isMethod('post')){
            $data = $request->all();
           // coupon validation
        //    dd($data);

        if($id==""){

            $rules = [
                'categories' => 'required',
                'brands' => 'required',
                'coupon_option' => 'required',
                'coupon_type' => 'required',
                'amount_type' => 'required',
                'amount' => 'required|numeric',
                'expiry_date' => 'required',
                'coupon_code' => 'unique:coupons',
            ];

        } else {

            $rules = [
                'categories' => 'required',
                'brands' => 'required',
                'coupon_option' => 'required',
                'coupon_type' => 'required',
                'amount_type' => 'required',
                'amount' => 'required|numeric',
                'expiry_date' => 'required',
            ];

        }


        $customMessages = [
            'categories.required' => 'Select Categories',
            'brands.required' => 'Select Brands',
            'coupon_option.required' => 'Select Coupon Option',
            'coupon_type.required' => 'Select Coupon Type',
            'amount_type.required' => 'Select Amount Type',
            'amount.required' => 'Enter Amount',
            'amount.numeric' => 'Enter Valid Amount',
            'expiry_date.required' => 'Enter Expiry Date',
        ];

        $this->validate($request, $rules, $customMessages);

        // convert categories array to string
        if(isset($data['categories'])){
            $categories = implode(',',$data['categories']);
        } else {
            $categories = "";
        }

        // convert brands array to string
        if(isset($data['brands'])){
            $brands = implode(',',$data['brands']);
        } else {
            $brands = "";
        }

        // convert users array to string
        if(isset($data['users'])){
            $users = implode(',',$data['users']);
        } else {
            $users = "";
        }

        if($data['coupon_option'] == "Automatic"){
            $coupon_code = Str::random(8);
        } else {
            $coupon_code = $data['coupon_code'];
        }

        $coupon->coupon_option = $data['coupon_option'];
        $coupon->coupon_code = $coupon_code;
        $coupon->categories = $categories;
        $coupon->brands = $brands;
        $coupon->users = $users;
        $coupon->coupon_type = $data['coupon_type'];
        $coupon->amount_type = $data['amount_type'];
        $coupon->amount = $data['amount'];
        $coupon->expiry_date = $data['expiry_date'];
        $coupon->status = 1;
        $coupon->save();
        return redirect('admin/coupons')->with('success_message',$message);

        }


        // get categories and their sub categories
        $getCategories = Category::getCategories();

        // get brands
        $getBrands = Brand::where('status', 1)->get()->toArray();

        // get user emails
        $getUsers = User::select('email')->where('status', 1)->get()->toArray();

        return view('admin.coupons.add_edit_coupon')->with(compact('title','coupon','getCategories','getBrands','getUsers','selCats','selUsers','selBrands'));
    }

}
