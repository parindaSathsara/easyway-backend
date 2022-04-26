<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerOrderController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\ListingImagesController;
use App\Http\Controllers\PartnerController;
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
Route::post('/registerEmployee',[AdminController::class,'registerEmployee']);

Route::post('/login',[AdminController::class,'userLogin']);
Route::post('/addService',[ServicesController::class,'addService']);
Route::post('/updateService',[ServicesController::class,'updateService']);
Route::post('/deleteService',[ServicesController::class,'deleteService']);

Route::post('/addListing',[ListingController::class,'addListing']);
Route::post('/partnerRegister',[PartnerController::class,'partnerRegister']);

Route::post('/partners/login',[PartnerController::class,'partnerLogin']);
Route::post('/partners/updateProfile',[PartnerController::class,'partnerUpdateAccount']);


Route::get('/partners/getAllPartners/',[PartnerController::class,'getAllPartners']);
Route::get('/partners/getPartnersByStatus/{status}',[PartnerController::class,'getPartnersByStatus']);
Route::get('/partners/getPartners/{id}',[PartnerController::class,'getPartners']);
Route::get('/partners/getListings',[ListingController::class,'getListings']);
Route::get('/partners/getListingsImages/{id}',[ListingController::class,'getListingsImages']);
Route::get('/partners/getListingsByID/{id}',[ListingController::class,'getListingsByID']);
Route::get('/partners/getListingsByPartnerID/{id}',[ListingController::class,'getListingsByPartnerID']);


Route::post('customers/registerCustomer',[CustomerController::class,'registerCustomer']);
Route::post('customers/loginCustomer',[CustomerController::class,'loginCustomer']);
Route::post('customers/addToCart',[CartController::class,'addToCart']);
Route::get('customers/getCarts/{id}',[CartController::class,'getCart']);
Route::get('customers/getCartsByID/{id}',[CartController::class,'getCartByID']);
Route::post('customers/deleteCart/{id}',[CartController::class,'deleteCartItem']);
Route::get('customers/getCartItemCount/{id}',[CartController::class,'getCartItemCount']);
Route::post('customers/placeOrder',[CustomerOrderController::class,'placeOrder']);

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
