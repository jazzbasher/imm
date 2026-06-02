<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TimeClockController;

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

Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
