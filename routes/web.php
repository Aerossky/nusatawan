<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DestinationController as AdminDestinationController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;



// resource route
// Route::resource('destinasi', DestinationController::class);

// admin route
Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');

// user route
Route::prefix('pengguna')->name('admin.users.')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('index');          // List User
    Route::get('/create', [UserController::class, 'create'])->name('create');  // Form Tambah
    Route::post('/', [UserController::class, 'store'])->name('store');         // Simpan Data
    Route::get('/{user}', [UserController::class, 'show'])->name('show');      // Detail User
    Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit'); // Form Edit
    Route::patch('/{user}', [UserController::class, 'update'])->name('update');  // Update Data
    Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');          // Hapus User
});

// destination route
Route::resource('destinasi', AdminDestinationController::class)->names('admin.destinations');
