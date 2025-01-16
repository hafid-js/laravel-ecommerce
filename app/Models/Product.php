<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ProductsAttribute;

class Product extends Model
{
    use HasFactory;

    public function category() {
        return $this->belongsTo('App\Models\Category','category_id')->with('parentcategory');
    }

    public function brand() {
        return $this->belongsTo('App\Models\Brand','brand_id');
    }

    public static function productsFilters() {
        $productsFilters['fabricArray'] = array('Cotton','Polyester','Wool');
        $productsFilters['sleeveArray'] = array('Full Sleeve','Half Sleeve','Short Sleeve','Sleeveless');
        $productsFilters['patternArray'] = array('Checked','Plain','Printed','Self','Solid');
        $productsFilters['fitArray'] = array('Reguler','Slim');
        $productsFilters['occasionArray'] = array('Casual','Formal');
        return $productsFilters;
    }

    public function images() {
        return $this->hasMany('App\Models\ProductsImage');
    }

    public function attributes() {
        return $this->hasMany('App\Models\ProductsAttribute');
    }

    public static function getAttributePrice($product_id, $size){
        $attributePrice = ProductsAttribute::where(
            [
                'product_id' => $product_id,
                'size' => $size,
                ])->first()->toArray();
        // for getting product discount
        $productDetails = Product::select(['product_discount','category_id','brand_id'])->where('id',$product_id)->first()->toArray();
        // for getting category discount
        $categoryDetails = Category::select(['category_discount'])->where('id',$productDetails['category_id'])->first()->toArray();
        // for getting brand discount
        $brandDetails = Brand::select(['brand_discount'])->where('id',$productDetails['brand_id'])->first()->toArray();

        if($productDetails['product_discount'] > 0) {
            // 1st case if there is any product discount
            $discount = $attributePrice['price'] * $productDetails['product_discount'] / 100;
            $discount_percent = $productDetails['product_discount'];
            $final_price = $attributePrice['price'] - $discount;
        } else if ($categoryDetails['category_discount'] > 0){
            // 2nd case if there is any category discount
            $discount = $attributePrice['price'] * $categoryDetails['category_discount'] / 100;
            $discount_percent = $productDetails['category_discount'];
            $final_price = $attributePrice['price'] - $discount;
        } else if($brandDetails['brand_discount'] > 0){
            // 3rd case if there is any brand discount
            $discount = $attributePrice['price'] * $brandDetails['brand_discount'] / 100;
            $discount_percent = $productDetails['brand_discount'];
            $final_price = $attributePrice['price'] - $discount;
        } else {
            // if there is no discount
            $discount = 0;
            $discount_percent = 0;
            $final_price = $attributePrice['price'];
        }

        return array('product_price' => $attributePrice['price'],'final_price' => $final_price,'discount' => $discount, 'discount_percent' => $discount_percent);
    }

    public static function productStatus($product_id){
        $productStatus = Product::select('status')->where('id',$product_id)->first();
        return $productStatus->status;
    }

    public static function getProductDetails($product_id) {
        $getProductDetails = Product::where('id',$product_id)->first()->toArray();
        return $getProductDetails;
    }


    public static function getAttributeDetails($product_id, $size) {
        $getAttributeDetails = ProductsAttribute::where([
            'product_id' => $product_id,
            'size' => $size
            ])->first()->toArray();
            return $getAttributeDetails;
    }

    public static function getProductImage($product_id) {
        $image = "";
        $productImageCount = ProductsImage::where('product_id',$product_id)->count();
        if($productImageCount > 0) {
            $getProductImage = ProductImage::select('image')->where('product_id',$product_id)->orderby('image_sort','DESC')->first();
            $image = $getProductImage->image;
        }
        return $image;
    }
}
