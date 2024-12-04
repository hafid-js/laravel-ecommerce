<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brand;
use Session;
use App\Models\AdminsRole;
use Auth;
use App\Models\Product;
use Image;

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

    public function addEditBrand(Request $request, $id=null){
        if ($id == "") {
            $title = "Add Brand";
            $brand = new Brand();
            $message = "Brand added successfully";
        } else {
            $title = "Edit Brand";
            $brand = Brand::find($id);
            $message = "Brand updated successfully";
        }
        if ($request->isMethod('post')) {
            $data = $request->all();

            if($id == 0) {
                $rules = [
                    'brand_name' => 'required',
                    'url' => 'required|unique:brands',
                ];
            } else {
                $rules = [
                'brand_name' => 'required',
                    'url' => 'required',
                ];
            }

            $customMessages = [
                'brand_name.required' => 'Brand name is required',
                'url.required' => 'Brand URL  is required',
                'url.unique' => 'Unique Brand URL is required'
            ];

            $this->validate($request, $rules, $customMessages);

        // upload brand image
        if ($request->hasFile('brand_image')) {
            $image_tmp = $request->file('brand_image');
            if($image_tmp->isValid()) {
                $extension = $image_tmp->getClientOriginalExtension();
                $imageName = rand(111,99999).'.'.$extension;
                $image_path = public_path('admin/images/brands/'.$imageName);
                Image::make($image_tmp)->save($image_path);
                $brand->brand_image = $imageName;
            }

        } else {
            $brand->brand_image == "";
        }


        // upload brand logo
        if ($request->hasFile('brand_logo')) {
            $image_tmp = $request->file('brand_logo');
            if($image_tmp->isValid()) {
                $extension = $image_tmp->getClientOriginalExtension();
                $imageName = rand(111,99999).'.'.$extension;
                $image_path = public_path('admin/images/brands/'.$imageName);
                Image::make($image_tmp)->save($image_path);
                $brand->brand_logo = $imageName;
            }

        } else {
            $brand->brand_logo == "";
        }


        // Remove Brand Discount from all products belongs to spesific Brand
        if(empty($data['brand_discount'])){
            $data['brand_discount'] = 0;
            if($id  != ""){
                $brandProducts = Product::where('brand_id', $id)->get()->toArray();
                foreach($brandProducts as $key => $product) {
                    if($product['discount_type'] == "brand"){
                        Product::where('id', $product['id'])->update(['discount_type' => '','final_price' => $product['product_price']]);
                    }
                }
            }
        }

        $brand->brand_name = $data['brand_name'];
        $brand->brand_discount = $data['brand_discount'];
        $brand->description = $data['description'];
        $brand->url = $data['url'];
        $brand->meta_title = $data['meta_title'];
        $brand->meta_description = $data['meta_description'];
        $brand->meta_keywords = $data['meta_keywords'];
        $brand->status = 1;
        $brand->save();
        return redirect('admin/brands')->with('success_message', $message);
        }
        return view('admin.brands.add_edit_brand')->with(compact('title','brand'));
    }
}
