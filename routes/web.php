<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/admin/dashboard', 'HomeController@index')->name('home');
Route::post('admin/dashboard', 'HomeController@authPayout')->name('dashboard_payout');
Route::get('/admin/payout_list', 'HomeController@payoutList')->name('payout');
Route::post('admin/payout_list', 'HomeController@authPayout')->name('payout_list');
Route::get('/admin/payout_export', 'HomeController@exportPayout')->name('payout_export');


Route::get('/admin/register', 'HomeController@register');
Route::get('/admin/delete_admin/{id}', 'Auth\RegisterController@deleteAdmin');
Route::post('/admin/delete_admin', 'Auth\RegisterController@confirmAdminDelete');

/* Application Forms routes starts here */
Route::get('/apply/yellow_traders', 'ApplicationsController@yellowTraders');
Route::post('/apply/yellow_traders', 'ApplicationsController@yellowTradersVal');
Route::get('/apply/junior_traders', 'ApplicationsController@juniorTraders');
Route::post('/apply/junior_traders', 'ApplicationsController@juniorTradersVal');
Route::get('/apply/corporate_traders', 'ApplicationsController@corporateTraders');
Route::post('/apply/corporate_traders', 'ApplicationsController@corporateTradersVal');
Route::get('/apply/topup_rollover', 'ApplicationsController@tuRoHome');
Route::post('/apply/topup_rollover', 'ApplicationsController@tuRoHomeVal');
Route::get('/apply/payment', 'ApplicationsController@payment');
Route::post('/apply/payment', 'ApplicationsController@paymentVal');
Route::get('/apply/calRoi/{amount}', 'ApplicationsController@calRoi');

/* Admin Routes */
Route::get('/admin/all_traders', function(){
    return view('admin.all_traders');
})->middleware('auth');
Route::get('/admin/yellow_traders', 'TradersController@yellow');
Route::get('/admin/junior_traders', 'TradersController@junior');
Route::get('/admin/corporate_traders', 'TradersController@corporate');
Route::get('/admin/search_trader', function(){
    return view('admin.search_trader');
});
Route::post('/admin/search_trader', 'TradersController@search');
Route::get('/admin/traders_export/{id}', 'TradersController@exportTraders')->name('traders_export');

Route::get('/admin/trader_profile', 'TraderProfileController@index');
Route::get('/admin/trader_profile/{id}', 'TraderProfileController@show');
Route::get('/admin/preview_mou/{id}', 'TraderProfileController@getMou');
Route::get('/admin/edit_trader/{id}', 'TraderProfileController@editTrader');
Route::post('/admin/edit_trader', 'TraderProfileController@updateTrader');
Route::get('/admin/delete_trader/{id}', 'TraderProfileController@deleteTrader');
Route::post('/admin/delete_trader', 'TraderProfileController@confirmTraderdelete');

Route::get('/admin/payments', 'PaymentController@recieved_payments');
Route::get('/admin/payments/{id}', 'PaymentController@viewPayment');
Route::post('/admin/payments', 'PaymentController@authPayment');


/* Preview email template views
Route::get('/emails/transaction', function (){
    return view('emails.transaction', ['amount'=>800, 'trader_id'=>'inv_8484834', 'trans_id'=>6363, 'inv_type'=>'new']);
});
Route::get('/emails/payout', function (){
    return view('emails.received_payments', ['amount'=>800000, 'monthly_roi'=>160000]);
});*/

/* Clear Cache
Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    dd("Cache is cleared");
});
*/
