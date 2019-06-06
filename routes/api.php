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

// API
Route::get('slot/{slot}', 'ApiController@findSlot');
Route::get('customer/{id}', 'ApiController@findCustomer');
Route::get('clients', 'ApiController@getClient');
Route::post('transaction', 'ApiController@transaction');
Route::post('customer', 'ApiController@customer');
