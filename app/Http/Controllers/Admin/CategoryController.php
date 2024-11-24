<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Image;

class CategoryController extends Controller
{
    public function categories() {
        $categories = Category::with('parentcategory')->get()->toArray();
        return view('admin.categories.categories')->with(compact('categories'));
    }

    public function updateCategoryStatus(Request $request)
    {
        if ($request->ajax()) {
            $data = $request->all();
            if($data['status'] == "Active") {
                $status = 0;
            } else {
                $status = 1;
            }
            Category::where('id', $data['category_id'])->update(['status' => $status]);
            return response()->json(['status' => $status,'category_id' => $data['category_id']]);
        }
    }

    public function deleteCategory($id)
    {
        Category::where('id',$id)->delete();
        return redirect()->back()->with('success_message','Cetegory deleted successfully');
    }

    public function addEditCategory(Request $request, $id=null){
        if ($id == "") {
            $title = "Add Category";
            $category = new Category();
            $message = "Category added successfully";
        } else {
            $title = "Edit Category";
        }
        if ($request->isMethod('post')) {
            $data = $request->all();

            $rules = [
                'category_name' => 'required',
                'url' => 'required|unique:categories',
            ];

            $customMessages = [
                'category_name.required' => 'Category name is required',
                'url.required' => 'Category URL  is required',
                'url.unique' => 'Unique Category URL is required'
            ];

            $this->validate($request, $rules, $customMessages);

        // upload category image
        if ($request->hasFile('category_image')) {
            $image_tmp = $request->file('category_image');
            if($image_tmp->isValid()) {
                $extension = $image_tmp->getClientOriginalExtension();
                $imageName = rand(111,99999).'.'.$extension;
                $image_path = public_path('admin/images/categories/'.$imageName);
                Image::make($image_tmp)->save($image_path);
                $category->category_image = $imageName;
            }

        } else {
            $category->category_image == "";
        }

        $category->category_name = $data['category_name'];
        $category->category_discount = $data['category_discount'];
        $category->url = $data['url'];
        $category->meta_title = $data['meta_title'];
        $category->meta_description = $data['meta_description'];
        $category->meta_keywords = $data['meta_keywords'];
        $category->status = 1;
        $category->save();
        return redirect('admin/categories')->with('success_message', $message);
        }
        return view('admin.categories.add_edit_category')->with(compact('title'));
    }
}
