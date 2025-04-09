<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\User\DestinationController;
use App\Models\Destination;
use Illuminate\Support\Facades\Route;



// resource route
Route::resource('destinasi', DestinationController::class);

// admin route
Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
