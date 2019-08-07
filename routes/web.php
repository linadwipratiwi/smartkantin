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

Route::post('manual-upload', 'CameraController@manualUpload');
Route::get('camera/render-js', 'CameraController@renderJS');
Route::post('camera', 'CameraController@store');
Route::get('camera', 'CameraController@camera');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/check-user', 'WelcomeController@checkUser');
    Route::post('profile', 'Backend\UserController@updateProfile');
    Route::get('profile', 'Backend\UserController@profile');
});

// Full Administrator
Route::group(['namespace' => 'Backend', 'middleware' => ['auth', 'role:administrator']], function () {
    Route::get('/', 'BackendController@index');

    Route::get('client/grid', 'ClientController@grid');
    Route::resource('client', 'ClientController');
    Route::resource('firmware', 'FirmwareController');
    Route::resource('category', 'CategoryController');
    Route::resource('customer', 'CustomerController');
    Route::resource('vending-machine', 'VendingMachine\VendingMachineController');
    Route::group(['prefix' => 'vending-machine', 'namespace' => 'VendingMachine'], function () {
        Route::resource('{id}/slot', 'VendingMachineSlotController');
        Route::get('{id}/stock/export', 'StockMutationController@export');
        Route::resource('{id}/stock', 'StockMutationController');
    });
    
    // Setting
    Route::resource('setting', 'SettingController');
    Route::get('report', 'ReportController@index');

    // User
    Route::resource('user', 'UserController', ['except' => ['show']]);
    Route::group(['prefix' => 'user'], function () {
        Route::get('set-permission', 'UserController@setPermission');
        Route::get('{user_id}/{role_id}', 'UserController@permission');
    });

    // Report
    Route::get('transaction', 'ReportController@transaction');
    Route::get('report', 'ReportController@report');
    
});

// Client
Route::group(['namespace' => 'Frontend', 'prefix' => 'front','middleware' => ['auth', 'role:client']], function () {
    Route::get('/', 'FrontendController@index');

    Route::get('customer/download', 'CustomerController@download');
    Route::resource('customer', 'CustomerController');
    Route::resource('vending-machine', 'VendingMachine\VendingMachineController');
    Route::group(['prefix' => 'vending-machine', 'namespace' => 'VendingMachine'], function () {
        Route::post('{id}/video', 'VendingMachineController@storeVideo');
        Route::get('{id}/video', 'VendingMachineController@_formVideo');
        Route::resource('{id}/slot', 'VendingMachineSlotController');
        Route::get('{id}/stock/export', 'StockMutationController@export');
        Route::resource('{id}/stock', 'StockMutationController');
    });
    
    // Setting
    Route::get('report', 'ReportController@index');
});

Auth::routes();
    