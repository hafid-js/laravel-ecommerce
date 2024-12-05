<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;
use Session;
use App\Models\AdminsRole;
use Auth;
use Image;

class BannersController extends Controller
{
    public function banners() {
        Session::put('page','banners');
        $banners = Banner::get()->toArray();
        $bannersModuleCount = AdminsRole::where(['subadmin_id' => Auth::guard('admin')->user()->id, 'module' => 'banners'])->count();
        $bannersModule = array();
        if(Auth::guard('admin')->user()->type == "admin") {
            $bannersModule['view_access'] = 1;
            $bannersModule['edit_access'] = 1;
            $bannersModule['full_access'] = 1;
        } else if ($bannersModuleCount == 0) {
            $message = "This feature is restricted for you!";
            return redirect('admin/dashboard')->with('error_message', $message);
        } else {

            $bannersModule = AdminsRole::where(['subadmin_id' => Auth::guard('admin')->user()->id, 'module' => 'banners'])->first()->toArray();
        }
        return view('admin.banners.banners')->with(compact('banners','bannersModule'));
    }

    public function updateBannerStatus(Request $request)
    {
        if ($request->ajax()) {
            $data = $request->all();
            if($data['status'] == "Active") {
                $status = 0;
            } else {
                $status = 1;
            }
            Banner::where('id', $data['banner_id'])->update(['status' => $status]);
            return response()->json(['status' => $status,'banner_id' => $data['banner_id']]);
        }
    }

    public function deleteBanner($id)
    {
        // get banner image
        $bannerImage = Banner::where('id', $id)->first();

        // get banner image path
        $image_path = 'admin/images/banners/';

        // delete banner image if exists in folder
        if (file_exists($image_path.$bannerImage->image)){
            unlink($image_path.$bannerImage->image);
        }


        Banner::where('id',$id)->delete();
        return redirect()->back()->with('success_message','Banner deleted successfully');
    }

    public function addEditBanner(Request $request, $id=null){
        if ($id == "") {
            $title = "Add Banner";
            $banner = new Banner();
            $message = "Banner added successfully";
        } else {
            $title = "Edit Banner";
            $banner = Banner::find($id);
            $message = "Banner updated successfully";
        }
        if ($request->isMethod('post')) {
            $data = $request->all();

                $rules = [
                    'type' => 'required',
                    'image' => 'required',
                ];

            $customMessages = [
                'type.required' => 'Banner Type is required',
                'image.required' => 'Banner Image is required',
            ];

            $this->validate($request, $rules, $customMessages);

        // upload banner image
        if ($request->hasFile('image')) {
            $image_tmp = $request->file('image');
            if($image_tmp->isValid()) {
                $extension = $image_tmp->getClientOriginalExtension();
                $imageName = rand(111,99999).'.'.$extension;
                $image_path = public_path('admin/images/banners/'.$imageName);
                Image::make($image_tmp)->save($image_path);
                $banner->image = $imageName;
            }

        } else {
            $banner->image == "";
        }

        if(!isset($data['title'])) {
            $data['title'] = "";
        }
        if(!isset($data['alt'])) {
            $data['alt'] = "";
        }
        if(!isset($data['link'])) {
            $data['link'] = "";
        }
        if(!isset($data['sort'])) {
            $data['sort'] = "";
        }


        $banner->title = $data['title'];
        $banner->alt = $data['alt'];
        $banner->link = $data['link'];
        $banner->sort = $data['sort'];
        $banner->type = $data['type'];
        $banner->status = 1;
        $banner->save();
        return redirect('admin/banners')->with('success_message', $message);
        }
        return view('admin.banners.add_edit_banner')->with(compact('title','banner'));
    }

}
