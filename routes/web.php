<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TimeClockController;
use App\Http\Controllers\VendorsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TimeOffRequestController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FreightLogController;
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

Route::get('/freightlog', [FreightLogController::class, 'index'])->name('freightlog');

Route::get('/freightlog/previousmonth', [FreightLogController::class, 'lastmonth'])->name('freightlog.lastmonth');

Route::get('/freightlog/create', [FreightLogController::class, 'create'])->name('freightlog.create');

Route::post('/freightlog/create/store', [FreightLogController::class, 'store'])->name('freightlog.store');

Route::get('/freightlog/edit/{id}', [FreightLogController::class, 'edit'])->name('freightlog.edit');

Route::post('/freightlog/updaterecord/{id}', [FreightLogController::class, 'updatelog'])->name('freightlog.zz');

Route::get('/vendors', [App\Http\Controllers\VendorsController::class, 'index'])->name('vendors');

Route::get('/test', [App\Http\Controllers\HomeController::class, 'test'])->name('test');

Route::get('/timeoff/newrequest', [App\Http\Controllers\TimeOffRequestController::class, 'requestform'])->name('timeoff.requestform');

Route::post('/timeoff/leavestore', [App\Http\Controllers\TimeOffRequestController::class, 'leavestore'])->name('leaverequest.store');

Route::get('/calendar', [TimeOffRequestController::class, 'index'])->name('calendar');
Route::get('/api/events', [TimeOffRequestController::class, 'getEvents']);

// Route::get('/notify', [TimeOffRequestController::class, 'submitforapproval']);



    Route::get('/send-test-email', function () {
        Mail::raw('This email was sent via the Mailgun HTTP API!', function ($message) {
            $message->to('mbartlett@industrialmill.com')
                    ->subject('MS Graph Test');  });  return 'Email sent successfully!';  });




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

        Route::get('/admin/users/create', [UserController::class, 'create'])->name('admin.users.create');
        Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store');

        Route::get('/manager/requests', [TimeOffRequestController::class, 'pendingrequests'])->name('manager.requests');

        Route::patch('/manager/requests/approve/{id}', [TimeOffRequestController::class, 'adminapprove'])->name('request.approve');

        Route::patch('/manager/requests/reject/{id}', [TimeOffRequestController::class, 'adminreject'])->name('request.reject');

        // Route::get('/payperiod', [TimeClockController::class, 'report'])->name('payperiod.report');

        Route::get('/dashboard/attendance', [DashboardController::class, 'attendancedash'])->name('dashboard.attendance');

        Route::get('/dashboard/attendance/payperiod/{period}', [DashboardController::class, 'payperiodattendance'])->name('attendance.periodreport');

        Route::get('/dashboard/attendance/{period}/{id}', [DashboardController::class, 'userattendance'])->name('attendance.details');

         Route::get('/admin/timeclock/{id}/{period}/{user}', [TimeClockController::class, 'clockeventdetail'])->name('clockevent.details');

         Route::get('/admin/leaverequest/{id}/{period}/{user}', [TimeOffRequestController::class, 'calendardetail'])->name('calendar.details');

         Route::post('/admin/timeclock/editpunch/{id}/{period}/{user}', [TimeClockController::class, 'editpunch'])->name('edit.timepunch');

         Route::post('/admin/timeclock/destroy/{period}/{user}', [TimeClockController::class, 'destroy'])->name('destroy.timepunch');

         Route::post('/admin/leaverequest/edit/{id}/{period}/{user}', [TimeOffRequestController::class, 'editleaverequest'])->name('edit.leaverequest');

         Route::post('/admin/leaverequest/destroy{period}/{user}', [TimeOffRequestController::class, 'destroy'])->name('destroy.leaverequest');

         Route::get('/admin/freight', [FreightLogController::class, 'adminreport'])->name('freightlog.report');



        // Route::get('/bustime', [TimeOffRequestController::class, 'businesstime']);

    });  /*** <- ends ADMIN middleware  -> ***/






});  /*** <- ends Auth user middleware  -> ***/


