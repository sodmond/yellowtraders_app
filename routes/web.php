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

Route::get('/', 'ApplicationsController@welcomePage');
Route::get('/new_investors', 'ApplicationsController@newInvestors');
Route::get('/returning_investors', 'ApplicationsController@returnInvestors');

Auth::routes();

Route::get('/admin/dashboard', 'HomeController@index')->name('home');
Route::get('/admin/report_analysis', 'HomeController@reportAnalysis')->name('report');
Route::get('/admin/report_analysis/{newDate}', 'HomeController@reportAnalysis')->name('weekReport');
Route::get('/admin/report_month/{filterMonth}/{filterYear}', 'HomeController@mReportAnalysis')->name('monthReport');
Route::post('admin/dashboard', 'HomeController@authPayout')->name('dashboard_payout');
Route::get('/admin/payout_list', 'HomeController@payoutList')->name('payout');
Route::post('admin/payout_list', 'HomeController@authPayout')->name('payout_list');
Route::get('/admin/payout_export', 'HomeController@exportPayout')->name('payout_export');
Route::get('/admin/payout_confirmed', 'HomeController@payoutConfirmed');
Route::post('/admin/search_payouts', 'HomeController@searchPayoutConfirmed');

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
Route::get('/apply/withdraw_capital', 'ApplicationsController@capitalWithdraw');
Route::post('/apply/withdraw_capital', 'ApplicationsController@capitalWithdrawAuth');

/* Admin Routes */
Route::get('/admin/all_traders', 'TradersController@allTraders');
Route::get('/admin/yellow_traders', 'TradersController@yellow');
Route::get('/admin/junior_traders', 'TradersController@junior');
Route::get('/admin/corporate_traders', 'TradersController@corporate');
Route::get('/admin/search_trader', 'TradersController@searchTraders');
Route::post('/admin/search_trader', 'TradersController@search');
Route::get('/admin/traders_export/{id}', 'TradersController@exportTraders')->name('traders_export');
Route::get('/admin/inactive_investors', 'TradersController@inactive');
Route::get('/admin/archived_investors', 'TradersController@archived');

Route::get('/admin/trader_profile', 'TraderProfileController@index');
Route::get('/admin/trader_profile/archtivate/{arstat}/{trader_id}', 'TraderProfileController@archtivate');
Route::get('/admin/trader_profile/{id}', 'TraderProfileController@show');
Route::get('/admin/preview_mou/{id}', 'TraderProfileController@getMou');
Route::get('/admin/preview_mou/send/{email}/{trader_id}', 'TraderProfileController@genPdfMou');
Route::get('/admin/edit_trader/{id}', 'TraderProfileController@editTrader');
Route::post('/admin/edit_trader', 'TraderProfileController@updateTrader');
Route::get('/admin/delete_trader/{id}', 'TraderProfileController@deleteTrader');
Route::post('/admin/delete_trader', 'TraderProfileController@confirmTraderdelete');

Route::get('/admin/all_payments', 'PaymentController@all_payments');
Route::get('/admin/payments', 'PaymentController@recieved_payments');
Route::get('/admin/payments/{id}', 'PaymentController@viewPayment');
Route::get('/admin/payments_filter', 'PaymentController@filter_payments');
Route::post('/admin/payments', 'PaymentController@authPayment');
Route::post('/admin/searchpayments', 'PaymentController@searchPayment')->name('searchPayment');


/* Preview email template views
Route::get('/emails/transaction', function (){
    return view('emails.transaction', ['amount'=>800, 'trader_id'=>'inv_8484834', 'trans_id'=>6363, 'inv_type'=>'new']);
});
Route::get('/emails/payout', function (){
    return view('emails.received_payments', ['amount'=>800000, 'monthly_roi'=>160000]);
});*/

//Route::get('/optimize-app', 'ApplicationsController@optimizeApp');

