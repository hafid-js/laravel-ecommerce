<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('/admin')->namespace('App\Http\Controllers\Admin')->group(function() {
    Route::match(['get', 'post'], 'login', 'AdminController@login');
    Route::group(['middleware' => ['admin']], function() {
        Route::get('dashboard', 'AdminController@dashboard');
        Route::match(['get', 'post'],'update-password','AdminController@updatePassword');
        Route::match(['get', 'post'],'update-details','AdminController@updateDetails');
        Route::post('check-current-password', 'AdminController@checkCurrentPassword');
        Route::get('logout', 'AdminController@logout');

        // Display CMS Page (CRUD - READ)
        Route::get('cms-pages', 'CmsController@index');
        Route::post('update-cms-page-status', 'CmsController@update');
        Route::match(['get', 'post'],'add-edit-cms-page/{id?}','CmsController@edit');
        Route::get('delete-cms-page/{id?}','CmsController@destroy');

        // Subadmins
        Route::get('subadmins','AdminController@subadmins');
        Route::post('update-subadmin-status', 'AdminController@updateSubadminStatus');
        Route::match(['get', 'post'],'add-edit-subadmin/{id?}','AdminController@addEditSubadmin');
        Route::get('delete-subadmin/{id?}','AdminController@deleteSubadmin');
        Route::match(['get', 'post'], 'update-role/{id}','AdminController@updateRole');

        // Categories
        Route::get('categories', 'CategoryController@categories');
        Route::post('update-category-status', 'CategoryController@updateCategoryStatus');
        Route::get('delete-category/{id?}','CategoryController@deleteCategory');
        Route::get('delete-category-image/{id?}','CategoryController@deleteCategoryImage');
        Route::match(['get','post'],'add-edit-category/{id?}', 'CategoryController@addEditCategory');

        // products
        Route::get('products','ProductsController@products');
        Route::post('update-product-status', 'ProductsController@updateProductStatus');
        Route::get('delete-product/{id?}','ProductsController@deleteProduct');
        Route::match(['get','post'],'add-edit-product/{id?}', 'ProductsController@addEditProduct');

        // product images
        Route::get('delete-product-image/{id?}','ProductsController@deleteProductImage');

        // product video
        Route::get('delete-product-video/{id?}','ProductsController@deleteProductVideo');

        // product attributes
        Route::post('update-attribute-status','ProductsController@updateAttributeStatus');
        Route::get('delete-attribute/{id?}','ProductsController@deleteAttribute');

        //brands
        Route::get('brands','BrandController@brands');
        Route::post('update-brand-status','BrandController@updateBrandStatus');
        Route::get('delete-brand/{id?}','BrandController@deleteBrand');
        Route::match(['get','post'],'add-edit-brand/{id?}', 'BrandController@addEditBrand');
        Route::get('delete-brand-image/{id?}','BrandController@deleteBrandImage');
        Route::get('delete-brand-logo/{id?}','BrandController@deleteBrandLogo');

        // banners
        Route::get('banners','BannersController@banners');
        Route::post('update-banner-status','BannersController@updateBannerStatus');
        Route::get('delete-banner/{id?}','BannersController@deleteBanner');

    });

});

