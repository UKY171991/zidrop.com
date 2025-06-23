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





Route::group(['namespace' => 'FrontEnd', 'middleware' => ['agentauth']], function () {
    Route::any('/agent/selected-item-invoice', 'AgentController@PrintSelectedItems')->name('agent.parcel.PrintSelectedItems');
    Route::get('/agent/dashboard', 'AgentController@dashboard');
    Route::get('agent/logout', 'AgentController@logout');
    Route::get('agent/parcels', 'AgentController@parcels');
    Route::get('/agent/parcel/{slug}', 'AgentController@parcelstatus');
    Route::any('agent/parcel/parcelup/receive-parcel', 'AgentController@parcelReceive')->name('agent.parcel.receive');
    Route::get('/agent/parcel/invoice/{id}', 'AgentController@invoice');
    Route::get('/agent/p2p-parcel/invoice/{id}', 'AgentController@p2pinvoice');
    Route::get('agent/parcel/transferhub/report/{id}', 'AgentController@transferhubreport')->name('transhubrpt');
    Route::get('agent/parcel/assigndel/report/{id}', 'AgentController@assigndelreport')->name('assigndelreport');
    Route::get('agent/parcel/returnmerchant/report/{id}', 'AgentController@returnmerchant')->name('returnmerchant');
    Route::get('agent/transferhub/report', 'AgentController@transferhubreportview')->name('transhubrptview');
    Route::get('agent/transfermerchant/report', 'AgentController@transmerchantrptview')->name('transmerchantrptview');
    Route::get('agent/assigntodel/report', 'AgentController@assigntodeltview')->name('assigntodeltview');
    Route::get('agent/returnhub/report', 'AgentController@returnhubrptview')->name('returnhubrptview');
    Route::get('agent/agent_get_trans_report_data', 'AgentController@gettransreportdata')->name('gettransreportdata');
    Route::get('agent/agent_get_assign_del_report_data', 'AgentController@geassigndelreportdata')->name('geassigndelreportdata');
    Route::get('agent/agent_get_return_report_data', 'AgentController@getrreturnreportdata')->name('getrreturnreportdata');
    Route::get('agent/merchant_get_return_report_data', 'AgentController@getmerreturnreportdata')->name('getmerreturnreportdata');
    Route::get('agent/pickup', 'AgentController@pickup');
    Route::post('agent/deliveryman/asign', 'AgentController@delivermanasiagn');
    Route::post('agent/dliveryman-asign/bulk-option', 'AgentController@bulkdeliverymanAssign');
    Route::post('agent/parcel/transfertohub/update', 'AgentController@transfertohub');
    Route::post('agent/parcel/returntohub/update', 'AgentController@returntohub');
    Route::post('agent/parcel/status-update', 'AgentController@statusupdate');
    Route::post('agent/parcel/status-mass-update', 'AgentController@massstatusupdate');
    Route::post('agent/pickup/deliveryman/asign', 'AgentController@pickupdeliverman');
    Route::post('agent/pickup/status-update', 'AgentController@pickupstatus');
    Route::post('agent/parcel/export', 'AgentController@export');
    Route::get('agent/profile/settings', 'AgentController@view');
    Route::get('agent/profile/request-paid', 'AgentController@requestpaid');
    Route::get('agent/commission/commission_history/{id}', 'AgentController@commission_history')->name('agent.payment.commission_history');
    Route::get('agent/commission/get_commission_history/{id}', 'AgentController@get_commission_history')->name('agent.payment.get_commission_history');
    Route::get('agent/commission/invoice_commission_history/{id}', 'AgentController@invoice_commission_history')->name('agent.payment.invoice_commission_history');

    Route::post('agent/profile/requestpaid', 'AgentController@requestpaidPost')->name('agent.parcel.requestpaid');
    Route::get('agent/payment/invoice-details/{paymentId}', 'AgentController@invoicedetails');
    Route::any('agent/parcel/track', 'AgentController@track');

    // New
    Route::any('agent/agent_get_parcel_data_all', 'AgentController@get_parcel_data_all');
    Route::any('agent/agent_get_parcel_data/{slug}', 'AgentController@get_parcel_data');
    Route::any('agent/get_parcel_by_qr/{trackCode}', 'AgentController@get_parcel_by_qr')->name('view.get_parcel_by_qr');
    Route::get('/agent/get/ppverify-payment/{reference}', 'PaymentController@agent_ppverifypayment');
    Route::any('/agent/get/store-payment', 'PaymentController@agent_p2psubmit');
    Route::any('/agent/p2p/p2p_store_cash_submit', 'PaymentController@p2p_store_cash_submit');


});

Route::group(['namespace' => 'FrontEnd', 'middleware' => ['agentauth']], function () {
    // password change routes
    Route::get('agent/parcel-create', 'AgentController@create')->name('agent.parcel-create');
    Route::get('agent/p2p-create', 'AgentController@p2pcreate')->name('agent.p2p-create');
    Route::post('agent/parcel/store', 'AgentController@parcelstore')->name('agent.parcel-store');
    Route::get('agent/parcel/edit/{id}', 'AgentController@parceledit')->name('agent.parcel-edit');
    Route::any('agent/parcel/update', 'AgentController@parcelupdate')->name('agent.parcel-update');

    // Ajax ROute
    Route::get('/get-town/{cityid}', [TownController::class, 'getTown'])->name('agent.get-town');
    Route::get('/get-tarrif/{cityid}', [ChargeTarifController::class, 'getTarrif'])->name('agent.get-tarrif');
});

Route::group(['as' => 'agent','prefix' => 'agent', 'middleware' => ['agentauth']], function () {
    Route::resource('/expense', ExpenseFrontController::class);
    Route::post('/parcel/status-sigle-update', [AgentController::class, 'singlestatusupdate'])->name('parcel.singlestatusupdate');
    Route::post('/parcel/return-to-merchant-update', [AgentController::class, 'returntomerchant'])->name('parcel.returntomerchant');
    Route::get('/get_parcel_history/{id}', function ($id) {
        $histories = App\History::where('parcel_id', $id)->get();
        return response()->json($histories);
    })->name('get_parcel_history');
});


Route::group(['namespace' => 'FrontEnd'], function () {

    
    // Agent Operation
    Route::get('agent/login', 'AgentController@loginform');
    Route::post('auth/agent/login', 'AgentController@login');
    Route::get('agent/forget/password', 'AgentController@passreset');
    Route::post('auth/agent/password/reset', 'AgentController@passfromreset');
    Route::get('/agent/resetpassword/verify', 'AgentController@resetpasswordverify');
    Route::post('auth/agent/reset/password', 'AgentController@saveResetPassword');

    
});


