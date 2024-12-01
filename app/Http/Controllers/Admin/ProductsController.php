<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductsImage;
use DB;
use Image;

class ProductsController extends Controller
{
    public function products() {
        $products = Product::with('category')->get()->toArray();
        return view('admin.products.products')->with(compact('products'));
    }

    public function updateProductStatus(Request $request)
    {
        if ($request->ajax()) {
            $data = $request->all();
            if($data['status'] == "Active") {
                $status = 0;
            } else {
                $status = 1;
            }
            Product::where('id', $data['product_id'])->update(['status' => $status]);
            return response()->json(['status' => $status,'product_id' => $data['product_id']]);
        }
    }

    public function deleteProduct(Request $request, $id = null)
    {
        Product::where('id',$id)->delete();
        return redirect()->back()->with('success_message','Product deleted successfully');
    }

    public function addEditProduct(Request $request, $id=null) {
        if($id == "") {
            $title = "Add Product";
            $product = new Product();
            $productData = array();
            $message = 'Product added successfully!';
        } else {
            $title = "Edit Product";
            $product = Product::find($id);
            $message = "Product updated successfully";
        }

        if($request->isMethod('post')) {
            $data = $request->all();

            $rules = [
                'category_id' => 'required',
                'product_name' => 'required|regex:/^[\pL\s\-]+$/u|max:200',
                'product_code' => 'required| regex:/^[\w-]*$/|max:30',
                'product_price' => 'required|numeric',
                'product_color' => 'required|regex:/^[\pL\s\-]+$/u|max:200',
                'family_color' => 'required|regex:/^[\pL\s\-]+$/u|max:200'
            ];

            $customMessages = [
                'category_id.required' => 'Category is required',
                'product_name.required' => 'Product Name is required',
                'product_name.regex' => 'Valid Product Name is required',
                'product_code.required' => 'Product Code is required',
                'product_code.regex' => 'Valid Product Code is required',
                'product_price.required' => 'Product Price is required',
                'product_price.numeric' => 'Valid Product Price is required',
                'product_color.required' => 'Product Color is required',
                'product_color.regex' => 'Valid Product Color is required',
                'family_color.required' => 'Family Color is required',
                'family_color.regex' => 'Valid Family Color is required'
            ];

            $this->validate($request, $rules, $customMessages);

            if ($request->hasFile('product_video')) {
                $video_tmp = $request->file('product_video');
                if($video_tmp->isValid()) {

                    // $videoName = $video_tmp->getClientOriginalName();
                    $videoExtension = $video_tmp->getClientOriginalExtension();
                    $videoName = rand().'.'.$videoExtension;
                    $videoPath = "admin/videos/products";
                    $video_tmp->move($videoPath, $videoName);

                    $product->product_video = $videoName;
                }
            }

            if (!isset($data['product_discount'])) {
                $data['product_discount'] = 0;
            }

            if (!isset($data['product_weight'])) {
                $data['product_weight'] = 0;
            }

            $product->category_id = $data['category_id'];
            $product->product_name = $data['product_name'];
            $product->product_code = $data['product_code'];
            $product->product_color = $data['product_color'];
            $product->family_color = $data['family_color'];
            $product->group_code = $data['group_code'];
            $product->product_price = $data['product_price'];
            $product->product_discount = $data['product_discount'];

            if(!empty($data['product_discount']) && $data['product_discount'] > 0) {
                $product->discount_type = 'product';
                $product->final_price = $data['product_price'] - ($data['product_price'] * $data['product_discount']) / 100;
            } else {
                $getCategoryDiscount = Category::select('category_discount')->where('id', $data['category_id'])->first();
                if($getCategoryDiscount->category_discount == 0) {
                    $product->discount_type = "";
                    $product->final_price = $data['product_price'];
                }
            }

            $product->product_weight = $data['product_weight'];
            $product->description = $data['description'];
            $product->wash_care = $data['wash_care'];
            $product->fabric = $data['fabric'];
            $product->pattern = $data['pattern'];
            $product->sleeve = $data['sleeve'];
            $product->fit = $data['fit'];
            $product->occasion = $data['occasion'];
            $product->search_keywords = $data['search_keywords'];
            $product->meta_title = $data['meta_title'];
            $product->meta_keywords = $data['meta_keywords'];
            $product->meta_description = $data['meta_description'];

            if (!empty($data['is_featured'])) {
                $product->is_featured = $data['is_featured'];
            } else {
                $product->is_featured = "No";
            }

            $product->status = 1;
            $product->save();

            if ($id == "") {
                $product_id = DB::getPdo()->lastInsert();
            } else {
                $product_id = $id;
            }

            if($request->hasFile('product_images')) {
                $images = $request->file('product_images');

                foreach($images as $key => $image) {
                    // generate temp image
                    $image_temp = Image::make($image);

                    //get image extension
                    $extension = $image->getClientOriginalExtension();

                    // generate new image name
                    $imageName = 'product-'.rand(1111,9999999).'.'.$extension;

                    // image path for small, medium and large images
                    $largeImagePath = 'admin/images/products/large/'.$imageName;
                    $mediumImagePath = 'admin/images/products/medium/'.$imageName;
                    $smallImagePath = 'admin/images/products/small/'.$imageName;

                    // upload the large, medium and small images after resize
                    Image::make($image_temp)->resize(1040,1200)->save($largeImagePath);
                    Image::make($image_temp)->resize(520,600)->save($mediumImagePath);
                    Image::make($image_temp)->resize(260,300)->save($smallImagePath);

                    // insert image name in products_images table
                    $image = new ProductsImage();
                    $image->image = $imageName;
                    $image->product_id = $product_id;
                    $image->status = 1;
                    $image->save();
                }
            }


            return redirect('admin/products')->with('success_message', $message, $title);

        }


        $getCategories = Category::getCategories();

        $productsFilters = Product::productsFilters();
        return view('admin.products.add_edit_product')->with(compact('title','getCategories','productsFilters','product'));
    }

    public function deleteProductVideo($id) {
        $productVideo = Product::select('product_video')->where('id', $id)->first();

        $product_video_path = 'admin/video/products/';

        if (file_exists($product_video_path.$productVideo->product_video)) {
            unlink ($product_video_path.$productVideo->product_video);
        }

        Product::where('id', $id)->update(['product_video' => '']);

        $message = "Product Video has been deleted successfully!";
        return redirect()->back()->with('success_message', $message);
    }
}
