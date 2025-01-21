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
use App\Http\Controllers\Front\UserController;
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

        // empty cart
        Route::post('empty-cart','ProductController@emptyCart');

        // user login
        Route::match(['get','post'], 'user/login','UserController@loginUser')->name('login');

        // user register
        Route::match(['get','post'], 'user/register','UserController@registerUser');

        // user confirm account
        Route::match(['get','post'], 'user/confirm/{code}','UserController@confirmAccount');

        Route::group(['middleware' => ['auth']], function() {
             // user logout
        Route::get('user/logout','UserController@userLogout');

        // user account
        Route::match(['get','post'],'user/account','UserController@account');

        // user change password
        Route::match(['get','post'],'user/update-password','UserController@updatePassword');

        // apply coupon
        Route::post('/apply-coupon','ProductController@applyCoupon');

        // Checkout
        Route::match(['get','post'],'/checkout','ProductController@checkout');

        });

        // forgot password
        Route::match(['get','post'], 'user/forgot-password','UserController@forgotPassword');

        // reset password
        Route::match(['get','post'],'user/reset-password/{code?}','UserController@resetPassword');

        // save delivery address
        Route::post('save-delivery-address','AddressController@saveDeliveryAddress');

        // get delivert address
        Route::post('get-delivery-address','AddressController@getDeliveryAddress');

        // remove delivery address
        Route::post('remove-delivery-address','AddressController@removeDeliveryAddress');

        // order thanks page
        Route::get('/thanks','ProductController@thanks');

        // my orders
        Route::get('/user/orders','OrderController@orders');

        // order details
        Route::get('/user/orders/{id}','OrderController@orderDetails');

        // paypal
        Route::get('/paypal','PaypalController@paypal');
        Route::post('pay','PaypalController@pay')->name('payment');
        Route::get('success','PaypalController@success');
        Route::get('error','PaypalController@error');

        // search products
        Route::get('search-products','ProductController@listing');

});

Route::get('download-order-pdf-invoice/{id}','App\Http\Controllers\Admin\OrderController@printPDFOrderInvoice');

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
        Route::post('update-subadmin-status', 'AdminController@updateSubAdminStatus');
        Route::match(['get', 'post'],'add-edit-subadmin/{id?}','AdminController@addEditSubadmin');
        Route::get('delete-subadmin/{id?}','AdminController@deleteSubadmin');
        Route::match(['get', 'post'], 'update-role/{id}','AdminController@updateRole');

        // Categories
        Route::get('categories','CategoryController@categories');
        Route::post('update-category-status', 'CategoryController@updateCategoryStatus');
        Route::get('delete-category/{id?}','CategoryController@updateCategory');
        Route::get('delete-category-image/{id?}','CategoryController@deleteCategoryImage');
        Route::match(['get','post'],'add-edit-category/{id?}', 'CategoryController@addEditCategory');

        // products
        Route::get('products','ProductsController@products');
        Route::post('update-product-status','ProductsController@updateProductStatus');
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
        Route::match(['get','post'],'add-edit-banner/{id?}', 'BannersController@addEditBanner');

        // coupons
        Route::get('coupons','CouponsController@coupons');
        Route::post('update-coupon-status','CouponsController@updateCouponStatus');
        Route::get('delete-coupon/{id?}','CouponsController@deleteCoupon');
        Route::match(['get','post'],'add-edit-coupon/{id?}','CouponsController@addEditCoupon');

        // Users
        Route::get('users','UserController@users');
        Route::post('update-user-status','UserController@updateUserStatus');

        // Orders
        Route::get('orders','OrderController@orders');
        Route::get('orders/{id}','OrderController@orderDetails');

        // update order status
        Route::post('update-order-status','OrderController@updateOrderStatus');

        // print html order invoice
        Route::get('print-order-invoice/{id}','OrderController@printHTMLOrderInvoice');

        // print pdf order invoice
        Route::get('print-pdf-order-invoice/{id}','OrderController@printPDFOrderInvoice');

        // Shipping Charges
        Route::get('shipping-charges','ShippingController@shippingCharges');
        Route::post('/update-shipping-status','ShippingController@updateShippingStatus');
        Route::match(['get','post'],'edit-shipping-charges/{id}','ShippingController@editShippingCharges');
    });

});

