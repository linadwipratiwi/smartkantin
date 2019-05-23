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
Route::get('vendors', 'ApiController@getVendor');
Route::get('inventories', 'ApiController@getInventory');
Route::get('items', 'ApiController@getItem');
Route::get('item-maintenance-activity/{item_id}', 'ApiController@getItemMaintenanceActivity');
