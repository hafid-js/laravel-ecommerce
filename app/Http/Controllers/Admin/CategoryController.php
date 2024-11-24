<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function categories() {
        $categories = Category::with('parentcategory')->get()->toArray();
        return view('admin.categories.categories')->with(compact('categories'));
    }
}
