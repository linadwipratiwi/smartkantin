<?php

use App\Http\Controllers\ApiController;
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
    Route::group(['middleware' => ['jwt']], function () {
      Route::get('user-login', 'MobileApiController@getUser');
    });

    Route::post('post-login', 'MobileApiController@postLogin');

    Route::get('scan-qr-code', 'MobileApiController@scanQRCode');
    Route::get('generate-qr-code', 'MobileApiController@generateQRCode');
    Route::get('search-transaction-customer/{stand_id}', 'MobileApiController@searchTransactionByCustomer');
    Route::post('firebase-token-store', 'MobileApiController@firebaseTokenStore');
    Route::post('multipayment', 'MobileApiController@multipayment');
    Route::post('topup', 'MobileApiController@topup');
    Route::post('transaction', 'MobileApiController@transaction');
    Route::get('customer/history-transaction/{id}', 'MobileApiController@customerHistoryTransaction');
    Route::get('customer/{id}', 'MobileApiController@findCustomer');
    Route::post('stand/history-transaction', 'MobileApiController@history');
    Route::post('login-client', 'MobileApiController@loginClient');
    Route::post('login', 'MobileApiController@login');
    Route::post('login-stand', 'MobileApiController@loginStand');
    Route::post('login-customer', 'MobileApiController@loginCustomer');
    Route::post('stand/transaction/bill-check', 'MobileApiController@billcheck');
    Route::post('stand/transaction/bill-pay', 'MobileApiController@billpayment');
    Route::get('client/list-stand/{client_id}', 'MobileApiController@listStand');
    Route::get('stand/saldo/{stand_id}', 'MobileApiController@getSaldoStand');
    Route::get('stand/get-withdraw/{stand_id}', 'MobileApiController@getWithdrawTransaction');
    Route::post('stand/request-withdraw', 'MobileApiController@withdrawRequest');
    Route::post('stand/transaction/order-check', 'MobileApiController@orderCheck');
    Route::post('stand/transaction/order-take', 'MobileApiController@orderTake');
    Route::post('stand/food', 'MobileApiController@getFood');
    Route::post('stand/set-jadwal-food', 'MobileApiController@setJadwalFood');
    Route::post('stand/update-stock', 'MobileApiController@setFoodStock');
    Route::post('change-password', 'MobileApiController@changePassword');
    Route::post('change-username', 'MobileApiController@changeUsername');
    Route::post('get-transaction', 'MobileApiController@getTransaction');
    Route::get('list-stand/{client_id}', 'MobileApiController@getListStand');
    Route::get('find-transaction-ip/{ip}', 'MobileApiController@findTransactionUpdatedOnIP');
    Route::post('transaction-kodepos', 'MobileApiController@transactionByKodepos');
  });
});

/** API vending machine */
Route::post('gopay/notification', 'ApiController@gopayRespon');
/** gopay respon */
Route::post('dana/notification', 'ApiController@danaRespon');
Route::group(['prefix' => 'v1'], function () {
  Route::group(['prefix' => 'vending-machine'], function () {
    Route::post('gopay_cancel', 'ApiController@gopayCancel');
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
    Route::get('transaction/status/{transaction_id}', 'ApiController@statusTransaction');
    Route::get('topup/status/{transaction_id}', 'ApiController@statusTopup');
  });
});


/** API stand */
Route::get('get-all-stock/{username}', 'ApiController@getStockAllVending');
Route::get('get-flag-client/{username}', 'ApiController@getFlagTransactionClient');
Route::post('stand/transaction', 'ApiController@standTransaction');

//nyoba
Route::get('permission/{id}', 'ApiController@getPermissions');
Route::get('tokos/{id}', 'ApiController@getMaterials');
Route::post('category', 'ApiController@insertCategories');
Route::post('updateCategory', 'ApiController@updateCategories');
