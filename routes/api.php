<?php

use Illuminate\Http\Request;

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

/*Route::middleware('auth:api')->get('/trader', function (Request $request) {
    return $request->user();
});*/

Route::middleware(['cors'])->group(function () {
    Route::post('/trader/login', 'API\AuthController@login');
    Route::post('/sendpwdresetcode', 'API\AuthController@initPasswordReset');
    Route::post('/confirmotp', 'API\AuthController@confirmOTP');
    Route::post('/password_change', 'API\AuthController@resetPassword');
});

Route::middleware(['auth:api', 'cors'])->group(function () {
    Route::post('/trader/sendpwdresetcode', 'API\AuthController@initPasswordReset');
    Route::post('/trader/confirmotp', 'API\AuthController@confirmOTP');
    Route::post('/trader/password_change', 'API\AuthController@resetPassword');

    Route::get('/trader/dashboard', 'API\TraderController@dashboard');
    Route::get('/trader/profile', 'API\TraderController@traderProfile');
    Route::get('/trader/investment', 'API\InvestmentController@activeInv');
    Route::get('/trader/investment_logs', 'API\InvestmentController@invLog');

    Route::get('/trader/topup', 'API\InvestmentController@topup')->name('checkTopupStatus');
    Route::post('/trader/topup', 'API\InvestmentController@topup')->name('topupForm');

    Route::get('/trader/rollover', 'API\InvestmentController@rollover')->name('checkRolloverStatus');
    Route::post('/trader/rollover', 'API\InvestmentController@rollover')->name('rolloverForm');

    Route::get('/convert_amount/{amount}', 'API\InvestmentController@getROI_duration');
    Route::post('/trader/payment', 'API\PaymentController@paymentProof');

    Route::get('/trader/withdraw_capital', 'API\PaymentController@capitalWithdrawal');
    Route::post('/trader/withdraw_capital', 'API\PaymentController@capitalWithdrawal');
    
    Route::get('/trader/notifications', 'API\NotificationController@get');
    Route::get('/trader/notifications/{id}', 'API\NotificationController@markAsRead');

    Route::get('/trader/logout', 'API\AuthController@logout');
});
