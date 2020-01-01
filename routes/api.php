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
Route::group(['prefix' => 'v1'], function () {
  Route::group(['prefix' => 'mobile', 'namespace' => 'Api'], function () {
    /** JWT Login */
    Route::group(['middleware' => ['jwt']], function() {
      Route::get('user-login', 'MobileApiController@getUser');
    });
    
    Route::post('post-login', 'MobileApiController@postLogin');

    Route::post('firebase-token-store', 'MobileApiController@firebaseTokenStore');
    Route::post('multipayment', 'MobileApiController@multipayment');
    Route::post('topup', 'MobileApiController@topup');
    Route::post('transaction', 'MobileApiController@transaction');
    Route::get('customer/history-transaction/{id}', 'MobileApiController@customerHistoryTransaction');
    Route::get('customer/{id}', 'MobileApiController@findCustomer');
    Route::post('stand/history-transaction', 'MobileApiController@history');
    Route::post('login-client','MobileApiController@loginClient');
    Route::post('login','MobileApiController@login');
    Route::post('login-stand','MobileApiController@loginStand');
    Route::post('stand/transaction/bill-check','MobileApiController@billcheck');
    Route::post('stand/transaction/bill-pay','MobileApiController@billpayment');
    Route::get('client/list-stand/{client_id}','MobileApiController@listStand');
    Route::post('stand/transaction/order-check','MobileApiController@orderCheck');
    Route::post('stand/transaction/order-take','MobileApiController@orderTake');
    Route::post('stand/food','MobileApiController@getFood');
    
  });
});

/** API vending machine */
Route::post('gopay/notification', 'ApiController@gopayRespon'); /** gopay respon */
Route::group(['prefix' => 'v1'], function () {
  Route::group(['prefix' => 'vending-machine'], function () {
    Route::post('topup', 'ApiController@topupTransaction');
    Route::post('slot', 'ApiController@findSlot');
    Route::get('customer/{id}', 'ApiController@findCustomer');
    Route::get('clients', 'ApiController@getClient');
    Route::get('firmware/{vending_machine_alias}', 'ApiController@getFirmware');
    Route::post('transaction/fail', 'ApiController@transactionFail');
    Route::get('transaction/{id}', 'ApiController@transactionDetail');
    Route::post('transaction', 'ApiController@transaction');
    Route::post('customer', 'ApiController@customer');
    Route::get('get-stock/{vending_alias}', 'ApiController@getStock');
    Route::get('get-flag-transaction/{vending_alias}', 'ApiController@getFlagTransaction');   
    Route::get('transaction/status/{transaction_id}','ApiController@statusTransaction');   
    Route::get('topup/status/{transaction_id}','ApiController@statusTopup');   
  });
});


/** API stand */
Route::get('get-all-stock/{username}', 'ApiController@getStockAllVending');
Route::get('get-flag-client/{username}', 'ApiController@getFlagTransactionClient');
Route::post('stand/transaction', 'ApiController@standTransaction');

