<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiMerchant;
use App\Http\Controllers\Api\ApiDeliveryman;
use App\Http\Controllers\Api\ApiMerchantDashboard;
use App\Http\Controllers\Api\ApiMerchantTopup;
use App\Http\Controllers\Api\ApiMerchantSummary;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\MerchantParcelController;
use App\Http\Controllers\Api\MerchantWalletController;
use App\Http\Controllers\Api\ParcelTrackController;

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

// Merchant Registration with OTP
Route::post('/merchant/register', [ApiMerchant::class, 'register']);
Route::post('/merchant/verify-otp', [ApiMerchant::class, 'verifyOtp']);
Route::post('merchant/request-otp', [ApiMerchant::class, 'requestEmailOtp']);
Route::post('merchant/register-with-otp', [ApiMerchant::class, 'registerWithOtp']);

Route::post('merchant/register', [ApiMerchant::class,"register"]);
Route::post('merchant/password/reset', [ApiMerchant::class,"passwordReset"]);
Route::post('merchant/password/verify', [ApiMerchant::class,"verifyAndChangePassword"]);
Route::post('merchant/password/change', [ApiMerchant::class, "changePassword"]);
Route::post('merchant/password/email-reset', [ApiMerchant::class, 'requestEmailPasswordReset']);
Route::post('merchant/password/email-verify', [ApiMerchant::class, 'verifyEmailPasswordReset']);
Route::get('merchant/dashboard/report/{id}', [ApiMerchant::class,"dashboard"]);
Route::get('merchant/profile', [ApiMerchant::class,"profile"]);
Route::put('merchant/profile/update', [ApiMerchant::class,"profileUpdate"]);
Route::get('services/choose', [ApiMerchant::class,"chooseservice"]);
Route::get('service', [ApiMerchant::class,"getServiceBySlug"]);
Route::get('service/{id}', [ApiMerchant::class,"getServiceById"]);
Route::get('cod/get', [ApiMerchant::class,"getCodCharge"]);
Route::post('merchant/cod-charge', [ApiMerchant::class, 'calculateCodCharge']);
Route::get('charge/get/{id}', [ApiMerchant::class,"getPackageCharges"]);
Route::post('merchant/delivery-charge', [ApiMerchant::class, 'calculateDeliveryCharge']);
Route::post('merchant/tax-charge', [ApiMerchant::class, 'calculateTaxCharge']);
Route::post('merchant/parcel/create', [ApiMerchant::class,"createParcel"]);
Route::put('merchant/parcel/update', [ApiMerchant::class,"parcelupdate"]);
Route::post('merchant/pickup/request', [ApiMerchant::class,"pickupRequest"]);
Route::post('merchant/parcel/payments', [ApiMerchant::class,"parcelPayments"]);
Route::get('merchant/parcel/invoice/{id}', [ApiMerchant::class, 'parcelInvoiceApi']);
Route::get('merchant/payments/{merchant_id}', [ApiMerchant::class,"payments"]);
Route::get('merchant/pickup/{startFrom}', [ApiMerchant::class,"pickup"]);
Route::get('merchant/parcels/{startFrom}', [ApiMerchant::class,"parcels"]);
Route::get('merchant/parcel/{id}', [ApiMerchant::class,"parceldetails"]);
Route::get('merchant/parcel/out-for-delivery', [ApiMerchant::class, 'parcelsOutForDelivery']);
Route::get('deliveryman/{id}', [ApiMerchant::class,"deliveryman"]);
Route::get('parcel/track/{trackid}', [ApiMerchant::class,"parceltrack"]);
Route::get('nearestZone/{state}', [ApiMerchant::class,"nearestZone"]);
Route::get('parcelType', [ApiMerchant::class,"parcelType"]);
Route::post('merchant/support',[ApiMerchant::class,"merchantSupport"]);
Route::get('merchant/notice',[ApiMerchant::class,"notice"]);
Route::get('merchant/return-payments', [ApiMerchant::class, 'apiReturnPayments']);
Route::get('merchant/remittance-payments', [ApiMerchant::class, 'remittancePayments']);

// Recent shipment status updates for merchant dashboard (mobile)
Route::get('merchant/dashboard/shipments', [ApiMerchantDashboard::class, 'recentShipments']);

// Merchant dashboard summary for mobile (Next Payout, Returned-To-Merchant Due, Overall Paid Amount)
Route::get('merchant/dashboard/summary', [ApiMerchantSummary::class, 'summary']);

// Get topup history for merchant (mobile)
Route::get('merchant/get/topup', [ApiMerchantTopup::class, 'getTopup']);

// Make a new topup for merchant (mobile)
//Route::post('merchant/topup', [ApiMerchantTopup::class, 'makeTopup']);

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
Route::get('cities', [LocationController::class, 'cities']);
Route::get('cities/{city}/towns', [LocationController::class, 'towns']);
Route::get('merchant/parcel-list', [MerchantParcelController::class, 'index']);
Route::get('merchant/wallet-history', [MerchantWalletController::class, 'history']);
Route::post('merchant/payment/return-invoice-details', [MerchantWalletController::class, 'returnInvoiceDetails']);
Route::get('merchant/parcel/pending', [ApiMerchant::class, 'pendingParcels']);
Route::get('merchant/subscriptions', [ApiMerchant::class, 'subscriptions']);
Route::get('subscription-plans', [ApiMerchant::class, 'subscriptionPlans']);
Route::get('merchant/bank-account', [ApiMerchant::class, 'getBankAccount']);
Route::put('merchant/bank-account', [ApiMerchant::class, 'updateBankAccount']);
Route::get('merchant/subscription-history', [ApiMerchant::class, 'subscriptionHistory']);
Route::post('merchant/same-day-pickup-request', [ApiMerchant::class, 'sameDayPickupRequest']);
Route::get('merchant/dashboard/current-month-stats', [ApiMerchant::class, 'currentMonthStats']);
Route::get('track/parcel/{trackid}', [ParcelTrackController::class, 'track']);