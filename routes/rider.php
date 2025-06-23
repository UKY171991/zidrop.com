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




Route::group(['namespace' => 'FrontEnd', 'middleware' => ['deliverymanauth']], function () {
    Route::get('deliveryman/dashboard', 'DeliverymanController@dashboard');
    Route::get('deliveryman/logout', 'DeliverymanController@logout');
    Route::get('deliveryman/parcels', 'DeliverymanController@parcels');
    Route::get('deliveryman/parcel/{slug}', 'DeliverymanController@parcelstatus');
    Route::get('deliveryman/parcel/invoice/{id}', 'DeliverymanController@invoice');
    Route::post('deliveryman/parcel/status-update', 'DeliverymanController@statusupdate');
    Route::get('deliveryman/pickup', 'DeliverymanController@pickup');
    Route::get('deliveryman/commission/history/{id}', 'DeliverymanController@commission_history')->name('del.com.historyy');
    Route::get('deliveryman/commission/get_history/{id}', 'DeliverymanController@get_history')->name('del.get_history');
    Route::get('deliveryman/commission/invoice_com_history/{id}', 'DeliverymanController@invoice_com_history')->name('del.invoice_com_history');
    Route::post('deliveryman/pickup/status-update', 'AgentController@pickupstatus');
    Route::post('deliveryman/parcel/export', 'DeliverymanController@export');
    Route::any('deliveryman/parcel/track', 'DeliverymanController@track');

});

Route::group(['namespace' => 'FrontEnd'], function () {

    
    // Deliveryman Operation
    Route::get('deliveryman/login', 'DeliverymanController@loginform');
    Route::post('auth/deliveryman/login', 'DeliverymanController@login');
    Route::get('deliveryman/forget/password', 'DeliverymanController@passreset');
    Route::post('auth/deliveryman/password/reset', 'DeliverymanController@passfromreset');
    Route::get('/deliveryman/resetpassword/verify', 'DeliverymanController@resetpasswordverify');
    Route::post('auth/deliveryman/reset/password', 'DeliverymanController@saveResetPassword');

    // new 
    Route::any('rider/rider_get_parcel_data_all', 'DeliverymanController@get_parcel_data_all');
    Route::any('rider/rider_get_parcel_data/{slug}', 'DeliverymanController@get_parcel_data');

    
});