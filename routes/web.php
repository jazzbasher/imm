<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TimeClockController;
use App\Http\Controllers\VendorsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TimeOffRequestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;


Auth::routes(['register' => false, 'reset' => true,]);



// Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLink']);

/******************************************************************************************** *
* ******************************************************************************************* *
* ***************************         AUTH users only        ******************************** *
* ******************************************************************************************* *
* ********************************************************************************************/


Route::middleware(['auth'])->group(function () {




Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/freightlog', [App\Http\Controllers\FreightLogController::class, 'index'])->name('freightlog');

Route::get('/freightlog/create', [App\Http\Controllers\FreightLogController::class, 'create'])->name('freightlog.create');

Route::post('/freightlog/create/store', [App\Http\Controllers\FreightLogController::class, 'store'])->name('freightlog.store');

Route::get('/vendors', [App\Http\Controllers\VendorsController::class, 'index'])->name('vendors');

Route::get('/test', [App\Http\Controllers\HomeController::class, 'test'])->name('test');

Route::get('/timeoff/newrequest', [App\Http\Controllers\TimeOffRequestController::class, 'requestform'])->name('timeoff.requestform');

Route::post('/timeoff/leavestore', [App\Http\Controllers\TimeOffRequestController::class, 'leavestore'])->name('leaverequest.store');

Route::get('/calendar', [TimeOffRequestController::class, 'index'])->name('calendar');
Route::get('/api/events', [TimeOffRequestController::class, 'getEvents']);

Route::get('/notify', [TimeOffRequestController::class, 'submitforapproval']);



    Route::get('/send-test-email', function () {
        Mail::raw('This email was sent via the Mailgun HTTP API!', function ($message) {
            $message->to('michaelbartlett@icloud.com')
                    ->subject('Mailgun API Test');  });  return 'Email sent successfully!';  });




    /******************************************************************************************** *
    * ***************************         Hourly users only      ******************************** *
    * ********************************************************************************************/


    Route::middleware(['hourly'])->group(function () {

        Route::get('/attendance', [TimeClockController::class, 'index'])->name('attendance.index');

        Route::post('/attendance/toggle', [TimeCLockController::class, 'toggle'])->name('attendance.toggle');

    }); /*** <- ends HOURLY middleware  -> ***/



    /******************************************************************************************** *
    * ***************************          ADMIN  only           ******************************** *
    * ********************************************************************************************/


    Route::middleware(['admin'])->group(function () {

        Route::get('/manager/requests', [TimeOffRequestController::class, 'pendingrequests'])->name('manager.requests');

        Route::patch('/manager/requests/approve/{id}', [TimeOffRequestController::class, 'adminapprove'])->name('request.approve');

        Route::patch('/manager/requests/reject/{id}', [TimeOffRequestController::class, 'adminreject'])->name('request.reject');

        Route::get('/bustime', [TimeOffRequestController::class, 'businesstime']);

    });  /*** <- ends ADMIN middleware  -> ***/






});  /*** <- ends Auth user middleware  -> ***/


