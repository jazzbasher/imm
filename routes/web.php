<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TimeClockController;
use App\Http\Controllers\VendorsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TimeOffRequestController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FreightLogController;
use App\Http\Controllers\APRemittanceController;
use App\Http\Controllers\POSReportController;
use App\Http\Controllers\EpicorReportController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\ContactController;
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

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('default');


Route::get('/vendors', [App\Http\Controllers\VendorsController::class, 'index'])->name('vendors');

Route::get('/test', [App\Http\Controllers\HomeController::class, 'test'])->name('test');

Route::get('/timeoff/newrequest', [App\Http\Controllers\TimeOffRequestController::class, 'requestform'])->name('timeoff.requestform');

Route::post('/timeoff/leavestore', [App\Http\Controllers\TimeOffRequestController::class, 'leavestore'])->name('leaverequest.store');

Route::get('/calendar', [TimeOffRequestController::class, 'index'])->name('calendar');
Route::get('/api/events', [TimeOffRequestController::class, 'getEvents']);


Route::get('/vendors/pricelistupload', [VendorsController::class, 'showForm']);
Route::post('/vendors/pricelistupload', [VendorsController::class, 'storeFile'])->name('pricelist.upload');

Route::get('/vendors/pricelist', [VendorsController::class, 'lenoxpricelist'])->name('pricelist.lennox');

Route::get('/vendors/credentials', [VendorsController::class, 'loginview'])->name('vendors.credentials');

Route::get('/internal/contacts', [ContactController::class, 'view'])->name('contacts.view');

// Deena report built in P21 report studio and removing from here.  Left in case it meets future need
// Route::get('/deenareport', [EpicorReportController::class, 'lineitemview'])->name('epicorreport.form');
// Route::post('/deenareport', [EpicorReportController::class, 'deena'])->name('epicorreport.post');

Route::get('/warehouse/drumlabels', [WarehouseController::class, 'drumlabels'])->name('warehouse.drumlabels');
Route::get('/warehouse/miscdocs', [WarehouseController::class, 'miscdocs'])->name('warehouse.miscdocs');







    /******************************************************************************************** *
    * ***************************         Accounting only        ******************************** *
    * ********************************************************************************************/


Route::middleware(['accounting'])->group(function () {

// Route::get('/remitform', [APRemittanceController::class,  'view'])->name('remit.dateform');

Route::get('/adtrusteemap', [APRemittanceController::class, 'map'])->name('remit.mapping');
Route::get('/admap/edit/{vendor}', [APRemittanceController::class, 'admapedit'])->name('remit.editadmap');
Route::patch('/admap/update/{id}', [APRemittanceController::class, 'admapupdate'])->name('admap.vendorupdate');
Route::post('/admap/destroy/{id}', [APRemittanceController::class, 'admapdestroy'])->name('admap.vendordestroy');
Route::get('/adtrusteemap/create', [APRemittanceController::class, 'admapcreate'])->name('admap.create');
Route::post('/adtrusteemap/create', [APRemittanceController::class, 'store'])->name('remit.create');

Route::get('/remitreport', [APRemittanceController::class,  'view'])->name('remit.dateform');

// change the below class report to view datatable view and export to Exports/ADRemitExport direct download
Route::post('/remitreport', [APRemittanceController::class,  'report'])->name('remit.report'); //change class to export
Route::post('/iscremitdownload', [APRemittanceController::class,  'export'])->name('remit.export');
Route::post('/spremitdownload', [APRemittanceController::class,  'serviceprovider'])->name('remit.serviceprovider');
Route::get('/spreport/{reportdate}', [APRemittanceController::class, 'spreport'])->name('remit.spreport');

Route::get('/sandvik', [POSReportController::class, 'view'])->name('sandvikpos.form');
Route::post('/sandvik', [POSReportController::class, 'sandvikexport'])->name('sandvik.report');


Route::get('/3m', [POSReportController::class, 'mmmpos'])->name('mmm.form');
Route::post('/3m', [POSReportController::class, 'mmmexport'])->name('mmm.report');

}); /*** <- ends ACCOUNTING middleware  -> ***/





    /******************************************************************************************** *
    * ***************************         Hourly users only      ******************************** *
    * ********************************************************************************************/


    Route::middleware(['hourly'])->group(function () {

        Route::get('/attendance', [TimeClockController::class, 'index'])->name('attendance.index');

        Route::post('/attendance/toggle', [TimeCLockController::class, 'toggle'])->name('attendance.toggle');

        Route::post('/lunch/toggle', [TimeCLockController::class, 'lunchtoggle'])->name('lunch.toggle');

    }); /*** <- ends HOURLY middleware  -> ***/



      /******************************************************************************************** *
    * ***************************         Freightlog users only      ******************************** *
    * ********************************************************************************************/


    Route::middleware(['freight'])->group(function () {

        Route::get('/freightlog', [FreightLogController::class, 'index'])->name('freightlog');

        Route::get('/freightlog/previousmonth', [FreightLogController::class, 'lastmonth'])->name('freightlog.lastmonth');

        Route::get('/freightlog/create', [FreightLogController::class, 'create'])->name('freightlog.create');

        Route::post('/freightlog/create/store', [FreightLogController::class, 'store'])->name('freightlog.store');

        Route::get('/freightlog/edit/{id}', [FreightLogController::class, 'edit'])->name('freightlog.edit');

        Route::post('/freightlog/updaterecord/{id}', [FreightLogController::class, 'updatelog'])->name('freightlog.zz');

    }); /*** <- ends FREIGHTLOG middleware  -> ***/


    /******************************************************************************************** *
    * ***************************          ADMIN  only           ******************************** *
    * ********************************************************************************************/


    Route::middleware(['admin'])->group(function () {

        Route::get('/admin/users/create', [UserController::class, 'create'])->name('admin.users.create');

        Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store');

        Route::get('/admin/users/manage', [UserController::class, 'manageusers'])->name('admin.manageusers');

        Route::get('/admin/user/edit/{id}', [UserController::class, 'edituser'])->name('admin.edituser');

        Route::patch('admin/users/update/{id}', [UserController::class, 'updateuser'])->name('admin.userupdate');

        Route::post('admin/users/destroy/{id}', [UserController::class, 'destroyuser'])->name('admin.userdestroy');

        Route::get('/manager/requests', [TimeOffRequestController::class, 'pendingrequests'])->name('manager.requests');

        Route::get('/manager/allrequests', [TimeOffRequestController::class, 'allrequests'])->name('manager.allrequests');

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

         Route::get('/timeoff/adminrequest', [App\Http\Controllers\TimeOffRequestController::class, 'adminrequest'])->name('timeoff.adminrequest');

         Route::post('/timeoff/adminrequest', [App\Http\Controllers\TimeOffRequestController::class, 'adminrequeststore'])->name('adminrequest.store');

         Route::get('/admin/freight', [FreightLogController::class, 'adminreport'])->name('freightlog.report');


    });  /*** <- ends ADMIN middleware  -> ***/






});  /*** <- ends Auth user middleware  -> ***/


