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

Route::get('/home', 'HomeController@index')->name('home');

/* Application Forms routes starts here */
Route::get('apply/yellow_traders', 'ApplicationsController@yellowTraders');
Route::post('apply/yellow_traders', 'ApplicationsController@yellowTradersVal');
Route::get('apply/junior_traders', 'ApplicationsController@juniorTraders');
Route::post('apply/junior_traders', 'ApplicationsController@juniorTradersVal');
Route::get('apply/corporate_traders', 'ApplicationsController@corporateTraders');
Route::post('apply/corporate_traders', 'ApplicationsController@corporateTradersVal');
Route::get('apply/topup_rollover', 'ApplicationsController@tuRoHome');
Route::post('apply/topup_rollover', 'ApplicationsController@tuRoHomeVal');
