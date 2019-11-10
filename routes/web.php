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

/** Administrator */
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

    // Stand
    Route::resource('stand', 'Stand\StandController');
    Route::group(['prefix' => 'stand', 'namespace' => 'Stand'], function () {
        Route::resource('{id}/slot', 'StandSlotController');
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

/** Client */
Route::group(['namespace' => 'Frontend', 'prefix' => 'front','middleware' => ['auth', 'role:client']], function () {
    Route::get('/', 'FrontendController@index');
    Route::get('topup', 'TopupController@index');

    Route::group(['prefix' => 'customer'], function () {
        Route::get('{id}/export', 'CustomerController@export');
        Route::post('topup/store', 'CustomerController@_topupStore');
        Route::get('{id}/topup/edit', 'CustomerController@_topupEdit');
        Route::get('{id}/topup/create', 'CustomerController@_topupCreate');
        Route::get('{id}/topup', 'CustomerController@_topupIndex');
        Route::get('import/store', 'CustomerController@storeImportDatabase');
        Route::post('import', 'CustomerController@storeImportTemp');
        Route::get('import/download-template', 'CustomerController@downloadTemplate');
        Route::get('import', 'CustomerController@import');
    });

    Route::resource('food', 'FoodController');
    Route::resource('customer', 'CustomerController');

    Route::resource('vending-machine', 'VendingMachine\VendingMachineController');
    Route::group(['prefix' => 'vending-machine', 'namespace' => 'VendingMachine'], function () {
        Route::post('{id}/video', 'VendingMachineController@storeVideo');
        Route::get('{id}/video', 'VendingMachineController@_formVideo');
        Route::post('product/stock-opname/update', 'VendingMachineSlotController@updateProduct');
        Route::get('{id}/product/stock-opname', 'VendingMachineSlotController@stockOpnameForm');
        Route::resource('{id}/slot', 'VendingMachineSlotController');
        Route::get('{id}/stock/export', 'StockMutationController@export');
        Route::resource('{id}/stock', 'StockMutationController');
    });

    Route::resource('stand', 'Stand\StandController');
    Route::group(['prefix' => 'stand', 'namespace' => 'Stand'], function () {
        Route::post('{id}/video', 'StandController@storeVideo');
        Route::get('{id}/video', 'StandController@_formVideo');
        Route::get('{id}/stock/export', 'StockMutationController@export');
        Route::post('product/stock-opname/update', 'StandSlotController@updateProduct');
        Route::get('{id}/product/stock-opname', 'StandSlotController@stockOpnameForm');
        Route::resource('{id}/product', 'StandSlotController');
        Route::resource('{id}/stock', 'StockMutationController');
    });
    
    // Setting
    Route::get('report', 'ReportController@index');
});

/** Customer */
Route::group(['namespace' => 'Frontend', 'prefix' => 'c','middleware' => ['auth', 'role:customer']], function () {
    Route::get('add-to-cart/{id}', 'PosController@_addToCart');
    Route::get('/', 'PosController@index');

});
Auth::routes();
    