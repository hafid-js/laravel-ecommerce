<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brand;
use Session;
use App\Models\AdminsRole;
use Auth;

class BrandController extends Controller
{
    public function brands() {
        Session::put('page','brands');
        $brands = Brand::get()->toArray();

        // set admin/subadmin permission for brands
        $brandsModuleCount = AdminsRole::where(['subadmin_id' => Auth::guard('admin')->user()->id, 'module' => 'brands'])->count();

        // $brandsModuleCount = 0;
        // $total = AdminsRole::selectRaw('SUM(view_access + edit_access + full_access) as total')->first();
        // $brandsModuleCount = $total->total;
        $brandsModule = array();
        if(Auth::guard('admin')->user()->type == "admin") {
            $brandsModule['view_access'] = 1;
            $brandsModule['edit_access'] = 1;
            $brandsModule['full_access'] = 1;
        } else if ($brandsModuleCount == 0) {
            $message = "This feature is restricted for you!";
            return redirect('admin/dashboard')->with('error_message', $message);
        } else {
            $brandsModule = AdminsRole::where(['subadmin_id' => Auth::guard('admin')->user()->id, 'module' => 'brands'])->first()->toArray();
        }
        return view('admin.brands.brands')->with(compact('brands','brandsModule'));
    }

    public function updateBrandStatus(Request $request)
    {
        if ($request->ajax()) {
            $data = $request->all();
            if($data['status'] == "Active") {
                $status = 0;
            } else {
                $status = 1;
            }
            Brand::where('id', $data['brand_id'])->update(['status' => $status]);
            return response()->json(['status' => $status,'brand_id' => $data['brand_id']]);
        }
    }

    public function deleteBrand($id)
    {
        Brand::where('id',$id)->delete();
        return redirect()->back()->with('success_message','Brand deleted successfully');
    }
}
