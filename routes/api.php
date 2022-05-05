<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerOrderController;
use App\Http\Controllers\DeliveryJobController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\ListingImagesController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\RiderController;
use App\Http\Controllers\ServicesController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/getservices', [ServicesController::class, 'getservices']);
Route::post('/registerEmployee',[AdminController::class,'insert']);

Route::post('/login',[AdminController::class,'userLogin']);
Route::post('/addService',[ServicesController::class,'addService']);
Route::post('/updateService',[ServicesController::class,'updateService']);
Route::post('/deleteService',[ServicesController::class,'deleteService']);

Route::post('/addListing',[ListingController::class,'addListing']);
Route::post('/partnerRegister',[PartnerController::class,'partnerRegister']);
Route::post('/riderRegister',[RiderController::class,'registerRider']);
Route::post('/riders/login',[RiderController::class,'riderLogin']);

Route::post('/listings/updateListing/{id}',[ListingController::class,'updateListing']);
Route::post('/listings/updateListingActive/{id}',[ListingController::class,'updateListingActive']);

Route::get('/riders/getRiders/{id}',[RiderController::class,'getRiders']);
Route::get('/riders/getAllRiders/',[RiderController::class,'getAllRiders']);
Route::get('/riders/getOrdersNotCollected/{id}',[RiderController::class,'getOrdersNotCollected']);
Route::get('/riders/getOrderByID/{id}',[RiderController::class,'getOrderByID']);
Route::get('/riders/getRidersByStatus/{status}',[RiderController::class,'getRidersByStatus']);
Route::post('/riders/riderUpdateProfile',[RiderController::class,'riderUpdateProfile']);

Route::get('/partners/getAllPartners/',[PartnerController::class,'getAllPartners']);
Route::get('/partners/getPartnersByStatus/{status}',[PartnerController::class,'getPartnersByStatus']);
Route::get('/partners/getPartners/{id}',[PartnerController::class,'getPartners']);
Route::get('/partners/getListings',[ListingController::class,'getListings']);
Route::get('/partners/getListingsImages/{id}',[ListingController::class,'getListingsImages']);
Route::get('/partners/getListingsByID/{id}',[ListingController::class,'getListingsByID']);
Route::get('/partners/getListingsByPartnerID/{id}',[ListingController::class,'getListingsByPartnerID']);
Route::get('/partners/getDeletedListingsByPartnerID/{id}',[ListingController::class,'getDeletedListingsByPartnerID']);
Route::post('/partners/login',[PartnerController::class,'partnerLogin']);
Route::post('/partners/updateProfile',[PartnerController::class,'partnerUpdateAccount']);

Route::get('/getDataCount',[AdminController::class,'getDataCounts']);

Route::post('customers/registerCustomer',[CustomerController::class,'registerCustomer']);
Route::post('customers/loginCustomer',[CustomerController::class,'loginCustomer']);
Route::post('customers/addToCart',[CartController::class,'addToCart']);
Route::get('customers/getCarts/{id}',[CartController::class,'getCart']);
Route::get('customers/getCartsByID/{id}',[CartController::class,'getCartByID']);
Route::post('customers/deleteCart/{id}',[CartController::class,'deleteCartItem']);
Route::get('customers/getCartItemCount/{id}',[CartController::class,'getCartItemCount']);

Route::post('deliveryjob/newjob',[DeliveryJobController::class,'addNewJob']);
Route::post('deliveryjob/updateDeliveryStatus',[DeliveryJobController::class,'updateDeliveryStatus']);


Route::post('customers/placeOrder',[CustomerOrderController::class,'placeOrder']);
Route::get('administration/getRecentOrders',[CustomerOrderController::class,'getOrders']);
Route::get('administration/getOrderCounts',[CustomerOrderController::class,'getOrdersCount']);
Route::get('administration/getServicesOrders',[CustomerOrderController::class,'getServicesOrders']);
Route::get('administration/getAllCustomers',[CustomerController::class,'getAllCustomers']);
Route::get('administration/getAllPartnersAdmins',[PartnerController::class,'getAllPartnersAdmins']);

Route::get('/partners/getPartnerDataCount/{id}',[PartnerController::class,'getPartnerDataCount']);
Route::get('/partners/getRecentOrdersPartners/{id}',[PartnerController::class,'getRecentOrdersPartners']);
Route::get('/partners/getPartnerSales/{id}',[PartnerController::class,'getPartnerSales']);

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
