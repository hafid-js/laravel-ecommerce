<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;
use Session;

class Cart extends Model
{
    use HasFactory;

    public function product() {
        return $this->belongsTo('App\Models\Product', 'product_id')->with('brand','images');
    }
}
