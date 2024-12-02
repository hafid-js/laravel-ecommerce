<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public function category() {
        return $this->belongsTo('App\Models\Category','category_id')->with('parentcategory');
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
}
