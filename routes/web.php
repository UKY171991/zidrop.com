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
use App\Http\Controllers\Admin\StatisticsDetailsController;
use App\Http\Controllers\Superadmin\SettingsController;
Auth::routes();

Route::get('/test', function(){
    return view('frontEnd.layouts.pages.agent.transferreport');
});

Route::get('/p2p', 'websitecontroller@p2p')->name('web.p2p');

Route::group(['prefix'=>'2fa'], function(){
    Route::get('/','LoginSecurityController@show2faForm');
    Route::post('/generateSecret','LoginSecurityController@generate2faSecret')->name('generate2faSecret');
    Route::post('/enable2fa','LoginSecurityController@enable2fa')->name('enable2fa');
    Route::post('/disable2fa','LoginSecurityController@disable2fa')->name('disable2fa');

    // 2fa middleware
    Route::post('/2faVerify', function () {
        return redirect(URL()->previous());
    })->name('2faVerify')->middleware('2fa');
});


//Clear Config cache:
Route::get('/cc', function () {
    $exitCode = Artisan::call('config:clear');
    $exitCode = Artisan::call('route:clear');
    $exitCode = Artisan::call('view:clear');
    $exitCode = Artisan::call('config:cache');
    return '<h1>All Config cleared</h1>';
});
Route::post('visitor/contact', [VisitorController::class, 'visitorcontact']);
Route::post('merchant/support', [VisitorController::class, 'merchantsupport']);
Route::post('career/apply', [VisitorController::class, 'careerapply']);


// 

// website routes
include __DIR__ . '/frontend.php';
// agent Routes 
include __DIR__ . '/agent.php';
// rider Routes 
include __DIR__ . '/rider.php';
// merchant Routes 
include __DIR__ . '/merchant.php';






Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => ['auth',  'author', 'admin', '2fa']], function () {
    Route::resource('/expense', ExpenseController::class);
    Route::resource('/status-description', ParcelTypeDescribeController::class);
    Route::get('/expense-report', 'ReportController@report')->name('expense-report');
    Route::get('/report/expense/search', 'ReportController@getExpenseSearchReports')->name('report.expense.search');
    Route::get('/charge-tariff', 'ChargeTarifController@index')->name('admin.charge-tarif');
    Route::get('/charge-tariff-upload', 'ChargeTarifController@upload')->name('admin.charge-tarif-upload');
    Route::post('/charge-tariff-upload', 'ChargeTarifController@uploadSubmit')->name('admin.charge-tarif-submit');
    Route::get('/town-tariff', 'TownController@index')->name('admin.town-tarif');
    Route::get('/town-tariff-upload', 'TownController@upload')->name('admin.town-tarif-upload');
    Route::post('/town-tariff-upload', 'TownController@uploadSubmit')->name('admin.town-tarif-submit');
    Route::resource('/admin-city', CityController::class);
    Route::resource('/statistics-details', StatisticsDetailsController::class);

    // Ajax ROute
    Route::get('/get-town/{cityid}', 'TownController@getTown');
    Route::get('/get-tarrif/{cityid}', 'ChargeTarifController@getTarrif');
    Route::get('/get-branch/{cityid}', 'ChargeTarifController@getBranch');
    Route::get('/get-merchant/{id}', 'ChargeTarifController@getMerchant');

    Route::resource('/expense-type', ExpenseTypeController::class);
});

Route::group(['as' => 'superadmin.', 'prefix' => 'superadmin', 'namespace' => 'Superadmin', 'middleware' => ['auth', 'superadmin', '2fa']], function () {
    // superadmin dashboard
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
    
    // user route
    Route::get('/user/add', 'UserController@add');
    Route::post('/user/save', 'UserController@save');
    Route::get('/user/edit/{id}', 'UserController@edit');
    Route::post('/user/update', 'UserController@update');
    Route::get('/user/manage', 'UserController@manage');
    Route::post('/user/inactive', 'UserController@inactive');
    Route::post('/user/active', 'UserController@active');
    Route::post('/user/delete', 'UserController@destroy');

    Route::get('smtp/configuration', 'SMTPConfigurationController@showConfiguration')->name('smtp.configuration.show');
    Route::post('smtp/configuration', 'SMTPConfigurationController@updateConfiguration')->name('smtp.configuration.show');

    // Settings route
    Route::get('/admin/settings', [SettingsController::class,'index'])->name('settings');
    Route::any('/admin/settings/update', [SettingsController::class,'update'])->name('settings.update');
});

// Live Search
Route::get('search_data/{keyword}', 'search\liveSearchController@SearchData');
Route::get('search_data', 'search\liveSearchController@SearchWithoutData');
// Ajax Route
// Route::get('/ajax-product-subcategory', 'editor\productController@getSubcategory');


Route::group(['as' => 'admin.', 'prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => ['auth', 'author', '2fa']], function () {

// Transform from admin
    Route::post('merchant-payment/bulk-option', 'DashboardController@bulkpayment');
    
});  
Route::get('/get-area/{id}',function($id){
    $area = Nearestzone::where('state',$id)->where('status',1)->get();
    return json_encode($area);
});


Route::group(['as' => 'admin.', 'prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => ['auth', 'admin', '2fa']], function () {
    // admin logout
    Route::get('logout', function () {
        Google2FA::logout();
        Auth::logout();
        return redirect('/login');
    })->name('logout');
    // admin dashboard
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
    // Route::post('merchant-payment/bulk-option', 'DashboardController@bulkpayment');
    // Nearest Zone Route
        Route::get('/branch', 'BranchController@index');
    Route::post('/branch/store', 'BranchController@store')->name('branch.store');
    Route::put('/branch/update/{id}', 'BranchController@update')->name('branch.update');
    Route::any('/branch/delete/{id}', 'BranchController@destroy')->name('branch.destroy');
    Route::get('/branch/show/{id}', 'BranchController@show')->name('branch.show');
    
    
    Route::get('/nearestzone/add', 'NearestzoneController@add');
    Route::post('/nearestzone/save', 'NearestzoneController@store');
    Route::get('/nearestzone/manage', 'NearestzoneController@manage');
    Route::get('/nearestzone/edit/{id}', 'NearestzoneController@edit');
    Route::post('/nearestzone/update', 'NearestzoneController@update');
    Route::post('/nearestzone/inactive', 'NearestzoneController@inactive');
    Route::post('/nearestzone/active', 'NearestzoneController@active');
    Route::post('/nearestzone/delete', 'NearestzoneController@destroy');

    // Delivery Charge Route
    Route::get('/deliverycharge/add', 'DeliveryChargeController@add');
    Route::post('/deliverycharge/save', 'DeliveryChargeController@store');
    Route::get('/deliverycharge/manage', 'DeliveryChargeController@manage');
    Route::get('/deliverycharge/edit/{id}', 'DeliveryChargeController@edit');
    Route::post('/deliverycharge/update', 'DeliveryChargeController@update');
    Route::post('/deliverycharge/inactive', 'DeliveryChargeController@inactive');
    Route::post('/deliverycharge/active', 'DeliveryChargeController@active');
    Route::post('/deliverycharge/delete', 'DeliveryChargeController@destroy');

    // Cod Charge Route
    Route::get('codcharge/add', 'CodChargeController@add');
    Route::post('codcharge/save', 'CodChargeController@store');
    Route::get('codcharge/manage', 'CodChargeController@manage');
    Route::get('codcharge/edit/{id}', 'CodChargeController@edit');
    Route::post('codcharge/update', 'CodChargeController@update');
    Route::post('codcharge/inactive', 'CodChargeController@inactive');
    Route::post('codcharge/active', 'CodChargeController@active');
    Route::post('codcharge/delete', 'CodChargeController@destroy');

    // District route
    Route::get('/district/add', 'DistrictController@index');
    Route::post('/district/save', 'DistrictController@store');
    Route::get('/district/manage', 'DistrictController@manage');
    Route::get('/district/edit/{id}', 'DistrictController@edit');
    Route::post('/district/update', 'DistrictController@update');
    Route::post('/district/inactive', 'DistrictController@inactive');
    Route::post('/district/active', 'DistrictController@active');
    Route::post('/district/delete', 'DistrictController@destroy');

      // Refresh Route
      Route::any('/refresh', 'RefreshController@index')->name('refresh');
      Route::any('/refresh/check', 'RefreshController@check')->name('refresh.check');
      Route::get('/auto-refresh',  'RefreshController@checkAutoRefresh')->name('auto-refresh');


       // Report Route
    Route::get('/report/sales', 'ReportController@salse')->name('report.salse');
    Route::get('/report/salse/search', 'ReportController@salseSearch')->name('report.salse.search');

    Route::get('/get_parcel_history/{id}', function ($id) {
        $histories = App\History::where('parcel_id', $id)->get();
        return response()->json($histories);
    })->name('get_parcel_history');
 
});


// Route::group(['as' => 'editor.', 'prefix' => 'editor', 'namespace' => 'Editor', 'middleware' => ['auth', 'admin']], function () {


//     Route::get('/parcel/edit/{id}', 'ParcelManageController@parceledit');

//     Route::post('/parcel/update', 'ParcelManageController@parcelupdate');
// });   

Route::group(['as' => 'editor.', 'prefix' => 'editor', 'namespace' => 'Editor', 'middleware' => ['auth', 'author', '2fa']], function () {
// Transfer from editor


    // Route::get('parcel/all', 'ParcelManageController@allparcel');
    Route::delete('parcel/delete/{id}', 'ParcelManageController@parceldelete');
    Route::get('parcel/{slug}', 'ParcelManageController@parcel');          
    Route::post('/dliveryman-asign/bulk-option', 'ParcelManageController@bulkdeliverymanAssign');
    Route::post('/returntohub/update', 'ParcelManageController@returntohub');
    Route::get('/processing/parcel', 'ParcelManageController@processing');
    Route::post('agent/asign', 'ParcelManageController@agentasign');
    Route::post('deliveryman/asign', 'ParcelManageController@deliverymanasign');
    Route::post('/parcel/status-update', 'ParcelManageController@statusupdate');
    Route::post('/parcel/status-mass-update', 'ParcelManageController@massstatusupdate');
    Route::get('/parcel/invoice/{id}', 'ParcelManageController@invoice');

    Route::post('pickupman/asign', 'ParcelManageController@pickupmanasign');

    Route::any('admin_get_parcel_data/{slug}', 'ParcelManageController@get_parcel_data');
    Route::any('admin_get_parcel_data_all', 'ParcelManageController@get_parcel_data_all');
    Route::any('deliveryman/view/get_parcel_by_qr/{trackCode}', 'ParcelManageController@get_parcel_by_qr')->name('deliveryman.view.get_parcel_by_qr');
    
        Route::post('/parcel/status-sigle-update', 'ParcelManageController@singlestatusupdate')->name('parcel.singlestatusupdate');
        Route::any('/selected-item-invoice', 'ParcelManageController@PrintSelectedItems')->name('parcel.PrintSelectedItems');

});  


Route::get('abc', function() {

    $parcels = \App\Parcel::whereIn('id', [2431, 2432, 2434, 2435, 2438])
        ->with(['pickupcity', 'deliverycity', 'pickuptown', 'deliverytown', 'merchant', 'deliverymen', 'agent', 'parceltype'])
        ->get();

    return view('pdf.pdf', compact('parcels'));

});
Route::group(['as' => 'editor.', 'prefix' => 'editor', 'namespace' => 'Editor', 'middleware' => ['auth', 'author', '2fa']], function () {
    
       // Transform from editor
    
       Route::get('/new/parcel-create', 'ParcelManageController@create');

       // agent Payment / 11 feb 25
       Route::any('/agent/agent_payment_list', 'ManagePaymentController@agent_payment_list')->name('agent.agent_payment_list');
       Route::any('/agent/agent_paid_payment_list/{id}', 'ManagePaymentController@agent_paid_payment_list')->name('agent.agent_paid_payment_list');
       Route::any('/agent/agent_get_com_payment/{id}', 'ManagePaymentController@agent_get_com_payment')->name('agent.agent_get_com_payment');
       Route::any('/agent/agent_get_com_payment_invoice/{id}', 'ManagePaymentController@agent_get_com_payment_invoice')->name('agent.agent_get_com_payment_invoice');
       Route::get('/agent/payment/export-csv','ManagePaymentController@exportAgentPaymentList')->name('agent.payment.export-csv'); 
       Route::any('/agent/commission-confirm-payment','ManagePaymentController@agentconfirmpayment')->name('agent.payment.export-pdf');
       
       // Deliveryman Payment / 11 feb 25
       Route::any('/deliveryman/deliveryman_payment_list', 'ManagePaymentController@deliveryman_payment_list')->name('deliveryman.deliveryman_payment_list');
       Route::any('/deliveryman/deliveryman_paid_payment_list/{id}', 'ManagePaymentController@deliveryman_paid_payment_list')->name('deliveryman.deliveryman_paid_payment_list');
       Route::any('/deliveryman/deliveryman_get_com_payment/{id}', 'ManagePaymentController@deliveryman_get_com_payment')->name('deliveryman.deliveryman_get_com_payment');
       Route::any('/deliveryman/deliveryman_get_com_payment_invoice/{id}', 'ManagePaymentController@deliveryman_get_com_payment_invoice')->name('deliveryman.deliveryman_get_com_payment_invoice');
       Route::get('/deliveryman/payment/export-csv','ManagePaymentController@exportDeliverymanPaymentList')->name('deliveryman.payment.export-csv'); 
       Route::any('/deliveryman/commission-confirm-payment','ManagePaymentController@deliverymanconfirmpayment')->name('deliveryman.payment.export-pdf');


}); 


Route::group(['as' => 'editor.', 'prefix' => 'editor', 'namespace' => 'Editor', 'middleware' => ['auth', 'editor', '2fa']], function () {
    // editor dashboard
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
    
     // Delivery Man Route
    Route::get('deliveryman/add', 'DeliverymanManageController@add');
    Route::post('deliveryman/save', 'DeliverymanManageController@save');
    Route::get('deliveryman/edit/{id}', 'DeliverymanManageController@edit');
    Route::post('deliveryman/update', 'DeliverymanManageController@update');
    Route::get('deliveryman/manage', 'DeliverymanManageController@manage');
    Route::post('deliveryman/inactive', 'DeliverymanManageController@inactive');
    Route::post('deliveryman/active', 'DeliverymanManageController@active');
    Route::post('deliveryman/delete', 'DeliverymanManageController@destroy');
    Route::get('deliveryman/view/{id}', 'DeliverymanManageController@view');
    Route::post('deliveryman-payment/bulk-option', 'DeliverymanManageController@bulkpayment');
    Route::get('/deliveryman/payment/invoice/{id}', 'DeliverymanManageController@paymentinvoice');
    Route::get('/deliveryman/payment/invoice-details/{id}', 'DeliverymanManageController@inovicedetails');
    
     //parcel manage
    // Route::get('parcel/all', 'ParcelManageController@allparcel');
    // Route::get('parcel/{slug}', 'ParcelManageController@parcel');
    // Route::post('/dliveryman-asign/bulk-option', 'ParcelManageController@bulkdeliverymanAssign');
    // Route::get('/processing/parcel', 'ParcelManageController@processing');
    // Route::post('agent/asign', 'ParcelManageController@agentasign');
    // Route::post('deliveryman/asign', 'ParcelManageController@deliverymanasign');
    // Route::post('/parcel/status-update', 'ParcelManageController@statusupdate');
    // Route::get('/parcel/invoice/{id}', 'ParcelManageController@invoice');
    // Route::post('pickupman/asign', 'ParcelManageController@pickupmanasign');
    
    // parcel route here
    // Route::get('/new/parcel-create', 'ParcelManageController@create');

    Route::post('/parcel/store', 'ParcelManageController@parcelstore');

    Route::get('/parcel/edit/{id}', 'ParcelManageController@parceledit');

    Route::any('/parcel/update', 'ParcelManageController@parcelupdate');
    
    //merchant payment
   //merchant payment
    Route::get('merchant/payment','ParcelManageController@merchantpaymentlist');
    Route::get('merchant/payment/export-csv','ParcelManageController@exportMerchantPaymentList')->name('merchant.payment.export-csv');
    Route::get('merchant/return-payment/export-csv','ParcelManageController@exportMerchantreturnPaymentList')->name('merchant.returnpayment.export-csv');
    Route::get('merchant/return-payment/export-pdf','ParcelManageController@exportMerchantPaymentListpdf')->name('merchant.returnpayment.export-pdf');
    Route::get('merchant/payment/export-pdf','ParcelManageController@exportMerchantPaymentListpdf')->name('merchant.payment.export-pdf');
    
    
    Route::get('merchant/returned_merchant','ParcelManageController@merchantreturnlist');
    Route::get('merchant/returned_merchant/{id}','ParcelManageController@returnpaymenthistory'); 
    Route::post('merchant/confirm-payment','ParcelManageController@merchantconfirmpayment');
    Route::post('merchant/confirm-returned-payment','ParcelManageController@merchantconfirmreturnpayment');
    Route::get('merchant/return-invoice/{id}','ParcelManageController@merchantInvoice')->name('return_invoice');
    // subscription history
    Route::get('merchant/subscription_history/{id}','ParcelManageController@merchantsubshisto');
    Route::any('merchant/subscription/disable/{id}','ParcelManageController@disablesubsplan');


    // agent payments
    Route::get('agent/payment-request', 'ParcelManageController@agentpaymentrequest');
    Route::post('agent/confirm-payment', 'ParcelManageController@agentpaymentconfirm');
    Route::get('agent/payment-invoice/{paymentId}','ParcelManageController@agentpaymentinvoice');

    // parcel Manage
    Route::get('/new/pickup', 'PickupManageController@newpickup')->name('new.pickup');
    Route::get('/pending/pickup', 'PickupManageController@pendingpickup');
    Route::get('/accepted/pickup', 'PickupManageController@acceptedpickup');
    Route::get('/cancelled/pickup', 'PickupManageController@cancelled');
    Route::post('pickup/agent/asign', 'PickupManageController@agentmanasign');
    Route::post('pickup/deliveryman/asign', 'PickupManageController@deliverymanasign');
    Route::post('/pickup/status-update', 'PickupManageController@statusupdate');
    //  ================ website oparation =====================

    // Logo route here
    Route::get('/logo/create', 'LogoController@create');
    Route::post('/logo/store', 'LogoController@store');
    Route::get('/logo/manage', 'LogoController@manage');
    Route::get('/logo/edit/{id}', 'LogoController@edit');
    Route::post('/logo/update', 'LogoController@update');
    Route::post('/logo/inactive', 'LogoController@inactive');
    Route::post('/logo/active', 'LogoController@active');
    Route::post('/logo/delete', 'LogoController@destroy');

    // Banner route here
    Route::get('/banner/create', 'BannerController@create');
    Route::post('/banner/store', 'BannerController@store');
    Route::get('/banner/manage', 'BannerController@manage');
    Route::get('/banner/edit/{id}', 'BannerController@edit');
    Route::post('/banner/update', 'BannerController@update');
    Route::post('/banner/inactive', 'BannerController@inactive');
    Route::post('/banner/active', 'BannerController@active');
    Route::post('/banner/delete', 'BannerController@destroy');

    // Service route here
    Route::get('/service/create', 'ServiceController@create');
    Route::post('/service/store', 'ServiceController@store');
    Route::get('/service/manage', 'ServiceController@manage');
    Route::get('/service/edit/{id}', 'ServiceController@edit');
    Route::post('/service/update', 'ServiceController@update');
    Route::post('/service/inactive', 'ServiceController@inactive');
    Route::post('/service/active', 'ServiceController@active');
    Route::post('/service/delete', 'ServiceController@destroy');

    // contact_info Operation
    Route::get('/contact_info/create', 'FeatureController@create_contact_info');
    Route::post('/contact_info/store', 'FeatureController@store_contact_info');

    // Payment api_info Operation
    Route::get('/api_info/create', 'PaymentAPIController@create_contact_info');
    Route::post('/api_info/store', 'PaymentAPIController@store_contact_info');

    // Feature Operation
    Route::get('/feature/create', 'FeatureController@create');
    Route::post('/feature/store', 'FeatureController@store');
    Route::get('/feature/manage', 'FeatureController@manage');
    Route::get('/feature/edit/{id}', 'FeatureController@edit');
    Route::post('/feature/update', 'FeatureController@update');
    Route::post('/feature/inactive', 'FeatureController@inactive');
    Route::post('/feature/active', 'FeatureController@active');
    Route::post('/feature/delete', 'FeatureController@destroy');

    // Price route here
    Route::get('price/create', 'PriceController@create');
    Route::post('price/store', 'PriceController@store');
    Route::get('price/manage', 'PriceController@manage');
    Route::get('price/edit/{id}', 'PriceController@edit');
    Route::post('price/update', 'PriceController@update');
    Route::post('price/inactive', 'PriceController@inactive');
    Route::post('price/active', 'PriceController@active');
    Route::post('price/delete', 'PriceController@destroy');

    // Blog route here
    Route::get('/blog/create', 'BlogController@create');
    Route::post('/blog/store', 'BlogController@store');
    Route::get('/blog/manage', 'BlogController@manage');
    Route::get('/blog/edit/{id}', 'BlogController@edit');
    Route::post('/blog/update', 'BlogController@update');
    Route::post('/blog/inactive', 'BlogController@inactive');
    Route::post('/blog/active', 'BlogController@active');
    Route::post('/blog/delete', 'BlogController@destroy'); 

    Route::get('/social-media/add', 'SocialController@index');
    Route::post('/social-media/save', 'SocialController@store');
    Route::get('/social-media/manage', 'SocialController@manage');
    Route::get('/social-media/edit/{id}', 'SocialController@edit');
    Route::post('/social-media/update', 'SocialController@update');
    Route::post('/social-media/unpublished', 'SocialController@unpublished');
    Route::post('/social-media/published', 'SocialController@published');
    Route::post('/social-media/delete', 'SocialController@destroy');

    // Partner route here
    Route::get('/partner/create', 'PartnerController@create');
    Route::post('/partner/store', 'PartnerController@store');
    Route::get('/partner/manage', 'PartnerController@manage');
    Route::get('/partner/edit/{id}', 'PartnerController@edit');
    Route::post('/partner/update', 'PartnerController@update');
    Route::post('/partner/inactive', 'PartnerController@inactive');
    Route::post('/partner/active', 'PartnerController@active');
    Route::post('/partner/delete', 'PartnerController@destroy');

    

    // About route here
    Route::get('/about/create', 'AboutController@create');
    Route::post('/about/store', 'AboutController@store');
    Route::get('/about/manage', 'AboutController@manage');
    Route::get('/about/edit/{id}', 'AboutController@edit');
    Route::post('/about/update', 'AboutController@update');
    Route::post('/about/inactive', 'AboutController@inactive');
    Route::post('/about/active', 'AboutController@active');
    Route::post('/about/delete', 'AboutController@destroy');

    Route::get('/clientfeedback/create', 'ClientfeedbackController@create');
    Route::post('/clientfeedback/store', 'ClientfeedbackController@store');
    Route::get('/clientfeedback/manage', 'ClientfeedbackController@manage');
    Route::get('/clientfeedback/edit/{id}', 'ClientfeedbackController@edit');
    Route::post('/clientfeedback/update', 'ClientfeedbackController@update');
    Route::post('/clientfeedback/inactive', 'ClientfeedbackController@inactive');
    Route::post('/clientfeedback/active', 'ClientfeedbackController@active');
    Route::post('/clientfeedback/delete', 'ClientfeedbackController@destroy');

    // career
    Route::get('career/create', 'CareerController@create');
    Route::post('career/store', 'CareerController@store');
    Route::get('career/manage', 'CareerController@manage');
    Route::get('career/edit/{id}', 'CareerController@edit');
    Route::post('career/update', 'CareerController@update');
    Route::post('career/inactive', 'CareerController@inactive');
    Route::post('career/active', 'CareerController@active');
    Route::post('career/delete', 'CareerController@destroy');

    // notice
    Route::get('notice/create', 'NoticeController@create');
    Route::post('notice/store', 'NoticeController@store');
    Route::get('notice/manage', 'NoticeController@manage');
    Route::get('notice/edit/{id}', 'NoticeController@edit');
    Route::post('notice/update', 'NoticeController@update');
    Route::post('notice/inactive', 'NoticeController@inactive');
    Route::post('notice/active', 'NoticeController@active');
    Route::post('notice/delete', 'NoticeController@destroy');
    Route::get('notice/{id}/publish/{status}', 'NoticeController@updatePublishStatus');

    // Gallery
    Route::get('gallery/create', 'GalleryController@create');
    Route::post('gallery/store', 'GalleryController@store');
    Route::get('gallery/manage', 'GalleryController@manage');
    Route::get('gallery/edit/{id}', 'GalleryController@edit');
    Route::post('gallery/update', 'GalleryController@update');
    Route::post('gallery/inactive', 'GalleryController@inactive');
    Route::post('gallery/active', 'GalleryController@active');
    Route::post('gallery/delete', 'GalleryController@destroy');

    // Note
    Route::get('note/create', 'NoteController@create');
    Route::post('note/store', 'NoteController@store');
    Route::get('note/manage', 'NoteController@manage');
    Route::get('note/edit/{id}', 'NoteController@edit');
    Route::post('note/update', 'NoteController@update');
    Route::post('note/inactive', 'NoteController@inactive');
    Route::post('note/active', 'NoteController@active');
    Route::post('note/delete', 'NoteController@destroy');
});

Route::group(['as' => 'author.', 'prefix' => 'author', 'namespace' => 'Author', 'middleware' => ['auth', 'author', '2fa']], function () {
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
    
    // merchant operation
    Route::get('/merchant-request/manage', 'MerchantOperationController@merchantrequest');
    Route::get('/merchant/manage', 'MerchantOperationController@manage');
    Route::get('/merchant/notice', 'MerchantOperationController@notice');
    Route::post('/merchant/notice-store', 'MerchantOperationController@noticestore');
    Route::get('/topup/history', 'MerchantOperationController@topuphistory');
    Route::post('/topup/add-balance', 'MerchantOperationController@addManualBalance')->name('topup.add-manual-balance');
    Route::post('/topup/subtract-balance', 'MerchantOperationController@subtractManualBalance')->name('topup.subtract-manual-balance');

    Route::get('/merchant/edit/{id}', 'MerchantOperationController@profileedit');
    Route::post('merchant/profile/edit', 'MerchantOperationController@profileUpdate');
    Route::post('merchant/inactive', 'MerchantOperationController@inactive');
    Route::post('merchant/active', 'MerchantOperationController@active');
    Route::get('merchant/view/{id}', 'MerchantOperationController@view');
    Route::delete('merchant/delete/{id}', 'MerchantOperationController@delete');
    Route::post('merchant/get/payment', 'MerchantOperationController@payment');
    Route::get('/merchant/payment/invoice/{id}', 'MerchantOperationController@paymentinvoice');
    Route::get('/merchant/return-payment/invoice/{id}', 'MerchantOperationController@returnpaymentinvoice');
    Route::post('/merchant/payment/invoice-details', 'MerchantOperationController@inovicedetails');
    Route::any('/merchant/payment/return-invoice-details', 'MerchantOperationController@returninovicedetails');
    Route::post('merchant/charge-setup', 'MerchantOperationController@chargesetup');
    Route::any('merchant/subscription/activation/{id}','MerchantOperationController@subsplanactivation');
    Route::any('merchant/subs/disable/{plan_id}/{merchant_id}', 'MerchantOperationController@disablesubsplan');


    
        // Department Route
    Route::get('department/add', 'DepartmentController@add');
    Route::post('department/save', 'DepartmentController@store');
    Route::get('department/manage', 'DepartmentController@manage');
    Route::get('department/edit/{id}', 'DepartmentController@edit');
    Route::post('department/update', 'DepartmentController@update');
    Route::post('department/inactive', 'DepartmentController@inactive');
    Route::post('department/active', 'DepartmentController@active');
    Route::post('department/delete', 'DepartmentController@destroy');

    // Employee Route
    Route::get('/employee/add', 'EmployeeController@add');
    Route::post('/employee/save', 'EmployeeController@save');
    Route::get('/employee/edit/{id}', 'EmployeeController@edit');
    Route::post('/employee/update', 'EmployeeController@update');
    Route::get('/employee/manage', 'EmployeeController@manage');
    Route::post('/employee/inactive', 'EmployeeController@inactive');
    Route::post('/employee/active', 'EmployeeController@active');
    Route::post('/employee/delete', 'EmployeeController@destroy');

    // Agent Manage Route
    Route::get('agent/add', 'AgentManageController@add');
    Route::post('agent/save', 'AgentManageController@save');
    Route::get('agent/edit/{id}', 'AgentManageController@edit');
    Route::post('agent/update', 'AgentManageController@update');
    Route::get('agent/manage', 'AgentManageController@manage');
    Route::post('agent/inactive', 'AgentManageController@inactive');
    Route::post('agent/active', 'AgentManageController@active');
    Route::get('agent/view/{id}', 'AgentManageController@view');
    Route::get('agent/payments/{id}', 'AgentManageController@payments');
    Route::get('/agent/notice', 'AgentManageController@notice')->name('agent.notice');
    Route::post('/agent/notice-store', 'AgentManageController@noticestore')->name('agent.notice.store');
    
    Route::post('agent-payment/bulk-option','AgentManageController@bulkpayment');
    Route::post('agent/delete', 'AgentManageController@destroy');
    Route::any('/agent/createpermission/update/{id}', 'AgentManageController@createpermission')->name('createpermission.update');

    

});

// other route
Route::group(['middleware' => ['auth']], function () {
    Route::get('password/change', 'ChangePassController@index');
    Route::post('password/updated', 'ChangePassController@updated');
});

/*Route::get('check-mail', function () {
    //\Illuminate\Support\Facades\Log::info(request()->headers);
    //\Illuminate\Support\Facades\Log::info(request()->all());
    $merchant = \App\Merchant::first();
    return view('mail.merchant-register', compact('merchant'));
});*/

Route::get('/parcel/pdf/{id}', 'App\Http\Controllers\Editor\ParcelManageController@pdf')->name('parcel.pdf');



Route::get('admin/session/true', function ($sessionName) {
    $beepSound = true;
    session()->put($sessionName, $beepSound);
    return response()->json(['success' => true]);
});
Route::get('admin/session/false', function () {
    $beepSound = false;
    session()->put('beepSound', $beepSound);
    return response()->json(['success' => true]);
});
Route::get('session/destroy', function ($sessionName) {
    session()->forget($sessionName);
    return response()->json(['success' => true]);
});

Route::get('/privacy-policy', function () {
    return view('privacy-policy');
})->name('privacy.policy');