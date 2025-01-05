<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coupon;
use App\Models\AdminsRole;
use Session;
use Auth;

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

}
