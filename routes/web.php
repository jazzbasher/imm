<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TimeClockController;
use App\Http\Controllers\VendorsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TimeOffRequestController;

Route::get('/', function () {
    return view('dashboard');
});

Route::get('/home', function () {
    return view('dashboard');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/attendance', [TimeClockController::class, 'index'])->name('attendance.index');
    Route::post('/attendance/toggle', [TimeCLockController::class, 'toggle'])->name('attendance.toggle');
});

Route::get('/freightlog', [App\Http\Controllers\FreightLogController::class, 'index'])->name('freightlog');

Route::get('/vendors', [App\Http\Controllers\VendorsController::class, 'index'])->name('vendors');

Route::get('/test', [App\Http\Controllers\HomeController::class, 'test'])->name('test');

Route::get('/phpinfo', function () {
    return phpinfo();
});

Route::get('/send-test-email', function () {
    Mail::raw('This email was sent via the Mailgun HTTP API!', function ($message) {
        $message->to('mbartlett@industrialmill.com')
                ->subject('Mailgun API Test');
    });

    return 'Email sent successfully!';
});

Route::get('/calendar', [TimeOffRequestController::class, 'index']);
Route::get('/api/events', [TimeOffRequestController::class, 'getEvents']);

Route::get('/notify', [TimeOffRequestController::class, 'submitforapproval']);

Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
