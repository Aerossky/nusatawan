<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DestinationController as AdminDestinationController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\User\DestinationController;
use App\Http\Controllers\User\DestinationSubmissionController;
use Illuminate\Support\Facades\Route;



// resource route
Route::get('/home', function () {
    return view('user.home');
});
// user route

// user dashboard
Route::get('/', function () {
    return view('user.home');
})->name('home');

// destination route
Route::resource('destinasi', DestinationController::class)
    ->parameters(['destinasi' => 'destination'])
    ->names('destinations');

// destination submission route
Route::get('/pengajuan-destinasi', [DestinationSubmissionController::class, 'index'])->name('destination-submission.index');
Route::post('/pengajuan-destinasi', [DestinationSubmissionController::class, 'store'])->name('destination-submission.store');


// admin route
Route::prefix('admin')->name('admin.')->group(function () {

    // admin dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // user route
    Route::prefix('pengguna')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{user}', [UserController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::patch('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
    });

    // destination route
    Route::resource('destinasi', AdminDestinationController::class)
        ->parameters(['destinasi' => 'destination'])
        ->names('destinations');

    // hapus foto Destinasi
    Route::delete('destinasi/{destination}/image/{image}', [AdminDestinationController::class, 'destroyImage'])->name('destinations.image.destroy');

    // kategori route
    Route::resource('kategori', CategoryController::class)
        ->parameters(['kategori' => 'category'])
        ->names('categories');
});
