<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use App\Models\ShippingCharge;
use App\Models\AdminsRole;
use Auth;

class ShippingController extends Controller
{
    public function shippingCharges() {
        Session::put('page','shipping');
        $shipping_charges = ShippingCharge::get()->toArray();

        // set admin/subadmin permission for shipping
        $shippingModuleCount = AdminsRole::where(['subadmin_id' => Auth::guard('admin')->user()->id, 'module' => 'shipping'])->count();
        $shippingModule = array();
        if(Auth::guard('admin')->user()->type == "admin") {
            $shippingModule['view_access'] = 1;
            $shippingModule['edit_access'] = 1;
            $shippingModule['full_access'] = 1;
        } else if ($shippingModuleCount == 0) {
            $message = "This feature is restricted for you!";
            return redirect('admin/dashboard')->with('error_message', $message);
        } else {

            $shippingModule = AdminsRole::where(['subadmin_id' => Auth::guard('admin')->user()->id, 'module' => 'shipping'])->first()->toArray();
        }

        return view('admin.shipping.shipping_charges')->with(compact('shipping_charges','shippingModule'));
    }

    public function updateShippingStatus(Request $request)
    {
        if ($request->ajax()) {
            $data = $request->all();
            if($data['status'] == "Active") {
                $status = 0;
            } else {
                $status = 1;
            }
            ShippingCharge::where('id', $data['shipping_id'])->update(['status' => $status]);
            return response()->json(['status' => $status,'shipping_id' => $data['shipping_id']]);
        }
    }
}
