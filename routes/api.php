<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\ListingImagesController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\VariationController;

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

Route::get('getservices', [ServicesController::class, 'getservices']);
Route::post('/registerEmployee',[AdminController::class,'registerEmployee']);

Route::post('/login',[AdminController::class,'userLogin']);
Route::post('/addService',[ServicesController::class,'addService']);
Route::post('/updateService',[ServicesController::class,'updateService']);
Route::post('/deleteService',[ServicesController::class,'deleteService']);

Route::post('/addListing',[ListingController::class,'addListing']);
Route::get('/partners/getListings',[ListingController::class,'getListings']);

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
