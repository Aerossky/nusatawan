<?php

use App\Http\Controllers\User\DestinationController;
use App\Models\Destination;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('admin.dashboard');
});

// resource route
Route::resource('destinasi', DestinationController::class);
