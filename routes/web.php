<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OfficerController;
use App\Http\Controllers\PickupScheduleController;
use App\Http\Controllers\WasteReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('waste-reports', WasteReportController::class);
Route::resource('pickup-schedules', PickupScheduleController::class);
Route::resource('officers', OfficerController::class);
