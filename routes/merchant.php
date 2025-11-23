<?php
 
// use Illuminate\Routing\Route;

use App\Nearestzone;

use App\Http\Controllers\VisitorController;
use App\Http\Controllers\FrontEnd\ExpenseFrontController;
use App\Http\Controllers\FrontEnd\AgentController;
use App\Http\Controllers\Admin\ExpenseController;
use App\Http\Controllers\Admin\ExpenseTypeController;
use App\Http\Controllers\Admin\VehicleController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\ChargeTarifController;
use App\Http\Controllers\Admin\TownController;
use App\Http\Controllers\Superadmin\SettingsController;





Route::group(['namespace' => 'FrontEnd', 'middleware' => ['merchantauth']], function () {
     Route::any('/merchant/selected-item-invoice', 'MerchantController@PrintSelectedItems')->name('merchant.parcel.PrintSelectedItems');
    // Merchant operation
    Route::get('merchant/dashboard', 'MerchantController@dashboard');
    Route::post('merchant/parcel/import', 'MerchantController@import');
    Route::post('merchant/parcel/export', 'MerchantController@export');
    Route::get('merchant/new-order/{slug}', 'MerchantController@parcelcreate');
    Route::get('merchant/pricing/{slug}', 'MerchantController@pricing');
    Route::post('merchant/payment/invoice-details', 'MerchantController@inovicedetails');
    Route::post('merchant/payment/return-invoice-details', 'MerchantController@returninovicedetails');
    Route::get('merchant/profile', 'MerchantController@profile');
    Route::get('merchant/subscriptions', 'MerchantController@subscriptions');
    Route::any('merchant/subscriptions/activate', 'MerchantController@subscriptions_activate');
    Route::get('merchant/get/verify-payment-subs/{reference}', 'PaymentController@verifypaymensubs');
    Route::get('merchant/subs/get/store-payment', 'PaymentController@storeSubsPayment');
    Route::get('merchant/profile/edit', 'MerchantController@profileEdit');
    Route::post('merchant/profile/edit', 'MerchantController@profileUpdate');
    Route::get('merchant/profile/settings', 'MerchantController@profileEdit');
    Route::get('merchant/stats', 'MerchantController@stats');
    Route::get('merchant/fraud-check', 'MerchantController@fraudcheck');
    Route::get('merchant/parcel/create', 'MerchantController@parcelcreate');
     Route::get('merchant/parcel/bulk-upload', 'MerchantController@parcelbulkupload');
     Route::get('merchant/parcel/template', 'MerchantController@bulkimporttemplate');
    Route::post('merchant/parcel/bulk-import','MerchantController@postbulkimport');
    Route::get('merchant/pickup', 'MerchantController@pickup');
    Route::get('merchant/support', 'MerchantController@support');
    Route::get('merchant/parcel/track', 'MerchantController@track');
    Route::get('merchant/parcel/invoice/{id}', 'MerchantController@invoice');
    // pickup request
    Route::post('merchant/pickup/request', 'MerchantController@pickuprequest');
    // parcel oparation
    Route::post('merchant/add/parcel', 'MerchantController@parcelstore');
    Route::get('merchant/parcels', 'MerchantController@parcels');
    Route::get('merchant/parcel/in-details/{id}', 'MerchantController@parceldetails')->name('merchant.parcel-details');
    Route::get('merchant/parcel/edit/{id}', 'MerchantController@parceledit');
    Route::post('merchant/update/parcel', 'MerchantController@parcelupdate');
    Route::post('/merchant/parcel/track/', 'MerchantController@parceltrack');
    Route::get('merchant/get/payments', 'MerchantController@payments');
    Route::get('merchant/get/return-payments', 'MerchantController@returnpayments');
    // parcel slug
    Route::get('merchant/parcel/{slug}', 'MerchantController@parcelstatus');
    Route::get('merchant/parcel_month/{slug}', 'MerchantController@parcelstatus_month');

    // password change routes
    Route::get('merchant/password/change', 'MerchantController@index');
    Route::post('auth/merchant/password/change','MerchantController@changepassword');
    
    // top up
    Route::get('merchant/get/topup', 'PaymentController@topup');
    Route::get('merchant/get/verify-payment/{reference}', 'PaymentController@verifypayment');
    // Route::get('merchant/get/subscription_history', 'MerchantController@subscription_history');
    Route::post('merchant/get/store-payment', 'PaymentController@storePayment');
    Route::any('merchant/subs/disable/{plan_id}/{merchant_id}', 'PaymentController@disablesubsplan');
    // Route::post('/pay', 'PaymentController@redirectToGateway')->name('pay');
    // Route::get('/payment/callback', 'PaymentController@handleGatewayCallback');

     // New
    //  Route::any('merchant/get_parcel_data_all', 'MerchantController@get_parcel_data_all');
     Route::any('merchant/get_parcel_data/{slug}', 'MerchantController@get_parcel_data');
     // ** remove this after developement of way bill
     // Route::any('merchant/get_parcel_dataTest/{slug}', 'MerchantController@get_parcel_dataTest');
     // Route::any('merchant/get_parcel_data_monthTest/{slug}', 'MerchantController@get_parcel_data_monthTest');

          Route::any('merchant/get_parcel_data_month/{slug}', 'MerchantController@get_parcel_data_month');
// ** remove this after developement of way bill
Route::get('merchant/test/parcel_month/{slug}', 'MerchantController@parcelstatus_monthTest');
});

Route::group(['prefix' => 'merchant', 'namespace' => 'Admin', 'middleware' => ['merchantauth']], function () {
    // Ajax ROute
    Route::get('/get-town/{cityid}', 'TownController@getTown');
    Route::get('/get-tarrif/{cityid}', 'ChargeTarifController@getTarrif');
    Route::get('/get-branch/{cityid}', 'ChargeTarifController@getBranch');
    Route::get('/get-merchant/{id}', 'ChargeTarifController@getMerchant');
});


Route::group(['namespace' => 'FrontEnd'], function () {

     // Merchant Operation
     Route::get('merchant/register', 'MerchantController@registerpage');
     Route::post('auth/merchant/register', 'MerchantController@register');
     Route::get('merchant/register-otp', 'MerchantController@register_otp');
     Route::get('merchant/register-verifyOtp', 'MerchantController@verifyOtp');
     Route::get('merchant/login', 'MerchantController@loginpage')->name('frontend.merchant.login');
     Route::post('merchant/login', 'MerchantController@login');
     Route::get('/merchant/phone-verify', 'MerchantController@phoneVerifyForm');
     Route::post('merchant/phone-resend', 'MerchantController@phoneresendcode');
     Route::post('/merchant/phone-verify', 'MerchantController@phoneVerify');
     Route::get('merchant/logout', 'MerchantController@logout');
     Route::get('merchant/forget/password', 'MerchantController@passreset');
     Route::post('auth/merchant/password/reset', 'MerchantController@passfromreset');
     Route::get('/merchant/resetpassword/verify', 'MerchantController@resetpasswordverify');
     Route::get('resend/password-reset/code/{id}', 'MerchantController@resendPasswordcode');
     Route::post('auth/merchant/reset/password', 'MerchantController@saveResetPassword');
     Route::post('auth/merchant/single-servicer', 'MerchantController@singleservice');
 
     Route::get('/merchant/get-area/{id}', 'FrontEndController@get_area');


});