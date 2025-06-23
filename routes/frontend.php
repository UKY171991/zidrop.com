<?php
 
// use Illuminate\Routing\Route;

use App\Nearestzone;

use App\Http\Controllers\FrontEnd\WebsiteController;
use App\Http\Controllers\Admin\TownController;
use App\Http\Controllers\Admin\ChargeTarifController;

Route::get('/web/get-town/{cityid}', [TownController::class, 'getTown']);
Route::get('/get-tarrif/{cityid}', [ChargeTarifController::class, 'getTarrif']); 


Route::group(['namespace' => 'FrontEnd'], function () {
    Route::get('/', 'FrontEndController@index');
    Route::get('/about-us', 'FrontEndController@aboutus');
    Route::get('/contact-us', 'FrontEndController@contact')->name('frontend.contact-us');
    Route::post('/contact-us/validate', 'FrontEndController@contactFormValidate')->name('frontend.contact-us.validate');
    Route::post('/contact-us', 'FrontEndController@contactSubmit')->name('frontend.contact-us');
    Route::get('/our-service/{id}', 'FrontEndController@ourservice');

    Route::get('/blog', 'FrontEndController@blog');
    Route::get('/understanding-tracking-statuslog', 'FrontEndController@understanding_tracking_status')->name('frontend.understanding-tracking-status');
    Route::get('/blog/details/{id}', 'FrontEndController@blogdetails');
    Route::get('/price', 'FrontEndController@price');
    Route::get('/pick-drop', 'FrontEndController@pickndrop');
    // Route::get('/branches', 'FrontEndController@branch');
    Route::get('/termscondition', 'FrontEndController@termscondition');
    Route::get('/faq', 'FrontEndController@faq');
    Route::get('/notice', 'FrontEndController@notice');
    Route::get('/branches', 'FrontEndController@branches')->name('frontend.branches');
    Route::get('/subscriptions', 'FrontEndController@subscriptions')->name('frontend.subscriptions');
    Route::get('/notice/{id}/{slug}', 'FrontEndController@noticedetails');
    Route::get('/gallery', 'FrontEndController@gallery');
    Route::get('/privacy-policy', 'FrontEndController@privacy');
    Route::get('/features', 'FrontEndController@features');
    Route::get('/features/details/{id}', 'FrontEndController@featuredetails');
    Route::post('/track/parcel/', 'FrontEndController@parceltrack');
    Route::get('/track/parcel/{id}', 'FrontEndController@parceltrackget');
    Route::get('delivery/charge/{id}', 'FrontEndController@delivryCharge');
    Route::get('/cost/calculate/{packageid}/{cod}/{weight}/{reciveZone}', 'FrontEndController@costCalculate');
    Route::get('cost/calculate/result', 'FrontEndController@costCalculateResult');

    //Route::get('/booking', 'BookingController@index');
    //Route::any('/booking-parcel', 'BookingController@parcelstore');

    //  new Routes
    Route::any('/web/get/store-payment', 'PaymentController@p2psubmit');
    Route::get('/p2p', 'WebsiteController@p2p')->name('web.p2p');
    Route::get('/web/get/ppverify-payment/{reference}', 'PaymentController@ppverifypayment');
    Route::get('/web/parcel/invoice/{id}', 'FrontEndController@webinvoice');

    Route::get('merchant/get/ppverify-payment/{reference}', 'PaymentController@ppverifypayment');

    Route::get('/tracking', 'TrackingController@tracking');
});

//Route::get('merchant/get/p2pverify-payment/{reference}', 'PaymentController@p2pverifypayment');

Route::get('/web/get-town/{cityid}', [TownController::class, 'getTown']);
Route::get('/web/get-tarrif/{cityid}', [ChargeTarifController::class, 'getTarrif']);

