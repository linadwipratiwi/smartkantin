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

// Download report history by item
Route::get('history/report/item/{id}', 'Backend\HistoryController@reportItem');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/check-user', 'WelcomeController@checkUser');
});

// Full Administrator
Route::group(['namespace' => 'Backend', 'middleware' => ['auth', 'role:administrator']], function () {
    Route::get('/', 'BackendController@index');
    Route::get('reject', 'BackendController@rejectForm');
    Route::post('reject', 'BackendController@reject');

    // Inventory
    Route::group(['prefix' => 'inventory', 'namespace' => 'Inventory'], function () {
        Route::get('history/download', 'InventoryHistoryController@download');
        Route::resource('history', 'InventoryHistoryController');
        Route::get('download', 'InventoryController@download');
        Route::resource('/', 'InventoryController');
    });

    // Master
    Route::group(['prefix' => 'master', 'namespace' => 'Master'], function () {
        Route::group(['prefix' => 'item'], function () {
            Route::delete('maintenance-activity/{id}', 'ItemController@destroyItemMaintenanceActivity');
            Route::post('maintenance-activity', 'ItemController@storeItemMaintenanceActivity');
            Route::get('maintenance-activity/edit/{id}', 'ItemController@editItemMaintenanceActivity');
            Route::get('maintenance-activity/create/{id}', 'ItemController@createItemMaintenanceActivity');
            Route::get('{id}/list', 'ItemController@_list');
        });
        Route::get('item/{id}/copy', 'ItemController@copy');
        Route::get('item/print-qrcode', 'ItemController@printQRcodeHistory');

        Route::resource('category', 'CategoryController');
        Route::resource('item', 'ItemController');
        Route::resource('vendor', 'VendorController');
        Route::resource('periode', 'PeriodeController');
    });
    
    // Submission
    Route::group(['prefix' => 'submission'], function () {
        // File Route
        Route::get('{id}/file', 'SubmissionController@_files');
        Route::get('file/create/{id}', 'SubmissionController@_createFile');
        Route::post('file', 'SubmissionController@_storeFile');
        Route::delete('file/{id}', 'SubmissionController@_deleteFile');
        Route::get('file/{id}/edit', 'SubmissionController@_editFile');
        Route::put('file/{id}', 'SubmissionController@_updateFile');

        // Request Route
        Route::get('{id}/cancel-request-epm', 'SubmissionController@_cancelRequestEpm');
        Route::get('{id}/send-request-epm', 'SubmissionController@_sendRequestEpm');
        Route::get('{id}/cancel-request-oh', 'SubmissionController@_cancelRequestOh');
        Route::get('{id}/send-request-oh', 'SubmissionController@_sendRequestOh');
        
        Route::get('{id}/approve-oh', 'SubmissionController@approveOh');
        Route::get('{id}/approve-epm', 'SubmissionController@approveEpm');
        Route::get('{id}/send-request', 'SubmissionController@_sendRequest');
        Route::get('{id}/print', 'SubmissionController@print');
        
        // Pending approval page
        Route::get('pending-approval-oh', 'SubmissionController@pendingApprovalOh');
        Route::get('pending-approval-epm', 'SubmissionController@pendingApprovalEpm');
    });
    Route::resource('submission', 'SubmissionController');
    
    // Setting
    Route::resource('setting', 'SettingController');

    // Certificate
    Route::resource('certificate', 'CertificateController', ['except' => ['show']]);
    Route::get('certificate/report', 'CertificateController@report');

    // Checklist Tools
    Route::resource('checklist', 'ChecklistController', ['except' => ['show']]);
    Route::group(['prefix' => 'checklist'], function () {
        Route::get('{id}/send-email', 'ChecklistController@sendEmail');
        Route::get('{id}/approve-request-checklist', 'ChecklistController@_approveRequestChecklist');
        Route::get('{id}/cancel-request-checklist', 'ChecklistController@_cancelRequestChecklist');
        Route::get('{id}/send-request-checklist', 'ChecklistController@_sendRequestChecklist');
        Route::get('{id}/send-request', 'ChecklistController@_sendRequest');
        Route::post('reject', 'ChecklistController@_rejectStore');
        Route::get('{id}/approve', 'ChecklistController@approve');
        Route::get('create/{id}', 'ChecklistController@create');
        Route::get('reminder', 'ChecklistController@reminder');
        Route::get('report', 'ChecklistController@report');
    });

    // History
    Route::resource('history', 'HistoryController', ['except' => ['show']]);
    Route::group(['prefix' => 'history'], function () {
        Route::get('{id}/approve-request-history', 'HistoryController@_approveRequestHistory');
        Route::get('{id}/cancel-request-history', 'HistoryController@_cancelRequestHistory');
        Route::get('{id}/send-request-history', 'HistoryController@_sendRequestHistory');
        Route::get('{id}/send-request', 'HistoryController@_sendRequest');

        Route::get('{id}/reference', 'HistoryController@_reference');
        Route::get('create/{id}', 'HistoryController@create');
        Route::get('{id}/request-approval', 'HistoryController@requestApproval');
        Route::get('{id}/approve', 'HistoryController@approve');
        Route::get('report', 'HistoryController@report');
    });

    // PTPP
    Route::group(['namespace' => 'Ptpp'], function () {
        Route::group(['prefix' => 'ptpp'], function () {
            Route::get('{id}/file', 'PtppController@_files');
            Route::get('file/create/{id}', 'PtppController@_createFile');
            Route::post('file', 'PtppController@_storeFile');
            Route::delete('file/{id}', 'PtppController@_deleteFile');
            Route::get('file/{id}/edit', 'PtppController@_editFile');
            Route::put('file/{id}', 'PtppController@_updateFile');

            // Request Route
            Route::get('{id}/cancel-request-rsd', 'PtppController@_cancelRequestRsd');
            Route::get('{id}/send-request-rsd', 'PtppController@_sendRequestRsd');
            Route::get('{id}/cancel-request-oh', 'PtppController@_cancelRequestOh');
            Route::get('{id}/send-request-oh', 'PtppController@_sendRequestOh');
            
            Route::get('{id}/approve-oh', 'PtppController@approveOh');
            Route::get('{id}/approve-rsd', 'PtppController@approveRsd');
            Route::get('{id}/send-request', 'PtppController@_sendRequest');
            Route::get('{id}/print', 'PtppController@print');
            
            // Pending approval page
            Route::get('pending-approval-oh', 'PtppController@pendingApprovalOh');
            Route::get('pending-approval-rsd', 'PtppController@pendingApprovalRsd');
            
            // Follow up
            Route::group(['prefix' => 'follow-up'], function () {
                Route::get('create/{id}', 'FollowUpController@createStep1');
                
                // Request Route
                Route::get('{id}/cancel-request-epm', 'FollowUpController@_cancelRequestEpm');
                Route::get('{id}/send-request-epm', 'FollowUpController@_sendRequestEpm');
                Route::get('{id}/approve-epm', 'FollowUpController@approveEpm');
                Route::get('{id}/send-request', 'FollowUpController@_sendRequest');
                Route::get('{id}/print', 'FollowUpController@print');
                
                // Pending approval page
                Route::get('pending-epm', 'FollowUpController@pendingApprovalEpm');
            });
            Route::resource('follow-up', 'FollowUpController');
            Route::post('{id}/verification', 'VerificationController@store');
            Route::get('{id}/verification', 'VerificationController@create');
            Route::group(['prefix' => 'verification'], function () {
                Route::get('{id}/print', 'VerificationController@print');
                Route::get('{id}/cancel-request-oh', 'VerificationController@_cancelRequestOh');
                Route::get('{id}/send-request-oh', 'VerificationController@_sendRequestOh');
                Route::get('{id}/approve-oh', 'VerificationController@approveOh');
                Route::get('pending-oh', 'VerificationController@pendingApprovalOh');
                Route::get('/', 'VerificationController@index');
            });

        });

        Route::resource('ptpp', 'PtppController');
    });

    // User
    Route::post('profile', 'UserController@updateProfile');
    Route::get('profile', 'UserController@profile');
    Route::resource('user', 'UserController', ['except' => ['show']]);
    Route::group(['prefix' => 'user'], function () {
        Route::get('set-permission', 'UserController@setPermission');
        Route::get('{user_id}/{role_id}', 'UserController@permission');
        
    });

    // Report
    Route::group(['prefix' => 'report'], function () {
        Route::get('ojt/download', 'ReportController@ojtDownload');
        Route::get('gate/download', 'ReportController@gateDownload');
        Route::get('working-permit/download', 'ReportController@workingPermitDownload');
        Route::get('guest/download', 'ReportController@guestDownload');
        Route::get('employee/download', 'ReportController@employeeDownload');
        Route::get('employee', 'ReportController@employee');
        Route::get('guest', 'ReportController@guest');
        Route::get('working-permit', 'ReportController@workingPermit');
        Route::get('gate', 'ReportController@gate');
        Route::get('ojt', 'ReportController@ojt');
    });

});


Auth::routes();
