<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CmsController;
use App\Http\Controllers\Admin\ProductsController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\BannersController;
use App\Http\Controllers\Front\IndexController;
use App\Http\Controllers\Front\ProductController;
use App\Models\Category;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::namespace('App\Http\Controllers\Front')->group(function() {
    Route::get('/',[IndexController::class,'index']);

    // listing/categories routes
    $catUrls = Category::select('url')->where('status',1)->get()->pluck('url');

    foreach ($catUrls as $key => $url){
        Route::get($url,'ProductController@listing');
    }

    // product detail page
    Route::get('product/{id}','ProductController@detail');

    // get product attribute price
    Route::post('get-attribute-price','ProductController@getAttributePrice');

    // add to cart
    Route::post('/add-to-cart','ProductController@addToCart');

    // Shopping Cart
    Route::get('cart','ProductController@cart');

    // update cart item quantity
    Route::post('/update-cart-item-qty','ProductController@updateCartItemQty');

    // delete cart item
    Route::post('delete-cart-item','ProductController@deleteCartItem');
});

Route::prefix('/admin')->namespace('App\Http\Controllers\Admin')->group(function() {
    Route::match(['get', 'post'], 'login', [AdminController::class,'login']);
    Route::group(['middleware' => ['admin']], function() {
        Route::get('dashboard', [AdminController::class,'dashboard']);
        Route::match(['get', 'post'],'update-password',[AdminController::class,'updatePassword']);
        Route::match(['get', 'post'],'update-details',[AdminController::class,'updateDetails']);
        Route::post('check-current-password', [AdminController::class,'checkCurrentPassword']);
        Route::get('logout', [AdminController::class,'logout']);

        // Display CMS Page (CRUD - READ)
        Route::get('cms-pages', [CmsController::class,'index']);
        Route::post('update-cms-page-status', [CmsController::class,'update']);
        Route::match(['get', 'post'],'add-edit-cms-page/{id?}',[CmsController::class,'edit']);
        Route::get('delete-cms-page/{id?}',[CmsController::class,'destroy']);

        // Subadmins
        Route::get('subadmins',[AdminController::class,'subadmins']);
        Route::post('update-subadmin-status', [AdminController::class,'updateSubAdminStatus']);
        Route::match(['get', 'post'],'add-edit-subadmin/{id?}',[AdminController::class,'addEditSubadmin']);
        Route::get('delete-subadmin/{id?}',[AdminController::class,'deleteSubadmin']);
        Route::match(['get', 'post'], 'update-role/{id}',[AdminController::class,'updateRole']);

        // Categories
        Route::get('categories',[CategoryController::class,'categories']);
        Route::post('update-category-status', [CategoryController::class,'updateCategoryStatus']);
        Route::get('delete-category/{id?}',[CategoryController::class,'updateCategory']);
        Route::get('delete-category-image/{id?}',[CategoryController::class,'deleteCategoryImage']);
        Route::match(['get','post'],'add-edit-category/{id?}', [CategoryController::class,'addEditCategory']);

        // products
        Route::get('products',[ProductsController::class,'products']);
        Route::post('update-product-status',[ProductsController::class,'updateProductStatus']);
        Route::get('delete-product/{id?}',[ProductsController::class,'deleteProduct']);
        Route::match(['get','post'],'add-edit-product/{id?}', [ProductsController::class,'addEditProduct']);

        // product images
        Route::get('delete-product-image/{id?}',[ProductsController::class,'deleteProductImage']);

        // product video
        Route::get('delete-product-video/{id?}',[ProductsController::class,'deleteProductVideo']);

        // product attributes
        Route::post('update-attribute-status',[ProductsController::class,'updateAttributeStatus']);
        Route::get('delete-attribute/{id?}',[ProductsController::class,'deleteAttribute']);

        //brands
        Route::get('brands',[BrandController::class,'brands']);
        Route::post('update-brand-status',[BrandController::class,'updateBrandStatus']);
        Route::get('delete-brand/{id?}',[BrandController::class,'deleteBrand']);
        Route::match(['get','post'],'add-edit-brand/{id?}', [BrandController::class,'addEditBrand']);
        Route::get('delete-brand-image/{id?}',[BrandController::class,'deleteBrandImage']);
        Route::get('delete-brand-logo/{id?}',[BrandController::class,'deleteBrandLogo']);

        // banners
        Route::get('banners',[BannersController::class,'banners']);
        Route::post('update-banner-status',[BannersController::class,'updateBannerStatus']);
        Route::get('delete-banner/{id?}',[BannersController::class,'deleteBanner']);
        Route::match(['get','post'],'add-edit-banner/{id?}', [BannersController::class,'addEditBanner']);

    });

});

