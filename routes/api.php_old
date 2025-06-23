<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiMerchant;
use App\Http\Controllers\Api\ApiDeliveryman;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('merchant/store-payment', [ApiMerchant::class,"storePayment"]);
Route::get('merchant/topup-history/{id}',[ApiMerchant::class,"topupHistory"]);
Route::post('merchant/login', [ApiMerchant::class,"login"]);
Route::post('merchant/otpverify', [ApiMerchant::class,"phoneVerify"]);
Route::post('merchant/register', [ApiMerchant::class,"register"]);
Route::post('merchant/password/reset', [ApiMerchant::class,"passwordReset"]);
Route::post('merchant/password/verify', [ApiMerchant::class,"verifyAndChangePassword"]);
Route::get('merchant/dashboard/report/{id}', [ApiMerchant::class,"dashboard"]);
Route::get('merchant/profile', [ApiMerchant::class,"profile"]);
Route::put('merchant/profile/update', [ApiMerchant::class,"profileUpdate"]);
Route::get('services/choose', [ApiMerchant::class,"chooseservice"]);
Route::get('service', [ApiMerchant::class,"getServiceBySlug"]);
Route::get('service/{id}', [ApiMerchant::class,"getServiceById"]);
Route::get('cod/get', [ApiMerchant::class,"getCodCharge"]);
Route::get('charge/get/{id}', [ApiMerchant::class,"getPackageCharges"]);
Route::post('merchant/parcel/create', [ApiMerchant::class,"createParcel"]);
Route::put('merchant/parcel/update', [ApiMerchant::class,"parcelupdate"]);
Route::post('merchant/pickup/request', [ApiMerchant::class,"pickupRequest"]);
Route::post('merchant/parcel/payments', [ApiMerchant::class,"parcelPayments"]);
Route::get('merchant/parcel/invoice/{id}', [ApiMerchant::class,"parcelinvoice"]);
Route::get('merchant/payments/{merchant_id}', [ApiMerchant::class,"payments"]);
Route::get('merchant/pickup/{startFrom}', [ApiMerchant::class,"pickup"]);
Route::get('merchant/parcels/{startFrom}', [ApiMerchant::class,"parcels"]);
Route::get('merchant/parcel/{id}', [ApiMerchant::class,"parceldetails"]);
Route::get('deliveryman/{id}', [ApiMerchant::class,"deliveryman"]);
Route::get('parcel/track/{trackid}', [ApiMerchant::class,"parceltrack"]);
Route::get('nearestZone/{state}', [ApiMerchant::class,"nearestZone"]);
Route::get('parcelType', [ApiMerchant::class,"parcelType"]);
Route::post('merchant/support',[ApiMerchant::class,"merchantSupport"]);
Route::get('merchant/notice',[ApiMerchant::class,"notice"]);

Route::get('notes', [ApiDeliveryman::class,"getNotes"]);
Route::post('deliveryman/login', [ApiDeliveryman::class,"login"]);
Route::post('deliveryman/password/reset', [ApiDeliveryman::class,"passwordReset"]);
Route::post('deliveryman/password/verify', [ApiDeliveryman::class,"verifyAndChangePassword"]);
Route::get('deliveryman/dashboard/report', [ApiDeliveryman::class,"dashboard"]);
Route::post('deliveryman/parcels/{search}', [ApiDeliveryman::class,"parcels"]);
Route::post('deliveryman/parcel/{parcelId}', [ApiDeliveryman::class,"parcel"]);
Route::post('deliveryman/parcel/status/update', [ApiDeliveryman::class,"parcelStatusUpdate"]);
Route::post('deliveryman/pickups/{search}', [ApiDeliveryman::class,"pickups"]);
Route::post('deliveryman/pickup/{pickupId}', [ApiDeliveryman::class,"pickup"]);
Route::post('deliveryman/pickup/status/update', [ApiDeliveryman::class,"pickupStatusUpdate"]);