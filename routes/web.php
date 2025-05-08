<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DestinationController as AdminDestinationController;
use App\Http\Controllers\Admin\DestinationSubmissionController as AdminDestinationSubmissionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\User\DestinationController;
use App\Http\Controllers\User\DestinationSubmissionController;
use App\Http\Controllers\User\FavoriteController;
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\User\ItineraryController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\ReviewController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


// AUTH LOGIN
// Auth::login(User::find(3));

// Auth::logout();

// Auth
Route::name('auth.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::name('user.')->group(function () {
    // Dashboard route
    Route::get('/', [HomeController::class, 'index'])->name('home');

    // About page
    Route::get('/tentang', function () {
        return view('user.about');
    })->name('about');

    // Profile routes
    Route::prefix('profil')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('show');
        Route::patch('/{user}', [ProfileController::class, 'update'])->name('update');
    });

    // Destination routes
    Route::match(['get', 'post'], 'destinasi', [DestinationController::class, 'index'])
        ->name('destinations.index');

    Route::resource('destinasi', DestinationController::class)
        ->only(['show']) // hanya `show` karena `index` sudah kamu override
        ->parameters(['destinasi' => 'destination:slug'])
        ->names(['show' => 'destinations.show']);

    // Itinerary routes
    Route::prefix('rencana-perjalanan')->as('itinerary.')->group(function () {
        Route::patch('/{itinerary}', [ItineraryController::class, 'update'])->name('update');
        Route::get('/', [ItineraryController::class, 'index'])->name('index');
        Route::get('/tambah-rencana', [ItineraryController::class, 'create'])->name('create');
        Route::post('/', [ItineraryController::class, 'store'])->name('store');
        Route::get('/{itinerary}/ubah', [ItineraryController::class, 'edit'])->name('edit');

        // itinerary destination routes
        Route::get('/{itinerary}', [ItineraryController::class, 'show'])->name('show');
        Route::post('/cari-destinasi-koordinat', [ItineraryController::class, 'searchDestinationsByCoordinates'])->name('destination.search.coordinates');
        Route::post('/cari-destinasi-nama', [ItineraryController::class, 'searchDestinationsByName'])->name('destination.search.name');
        Route::post('/tambah-destinasi', [ItineraryController::class, 'addDestinationItinerary'])->name('destination.add');
        Route::post('/hapus-destinasi', [ItineraryController::class, 'removeDestinationFromItinerary'])->name('destination.remove');

        Route::get('/destinasi/{id}/detail', [ItineraryController::class, 'getDestinationDetails'])
            ->name('destination.detail');

        // Route untuk update destinasi
        Route::post('/destinasi/update', [ItineraryController::class, 'updateDestination'])
            ->name('destination.update');
    });

    // Destination submission routes
    Route::prefix('pengajuan-destinasi')->name('destination-submission.')->group(function () {
        Route::get('/', [DestinationSubmissionController::class, 'create'])->name('create');
        Route::post('/', [DestinationSubmissionController::class, 'store'])->name('store');
    });

    // Favorite routes
    Route::prefix('destinasi-favorit')->name('destination-favorite.')->group(function () {
        Route::get('/', [FavoriteController::class, 'index'])->name('index');
    });

    // Like routes
    Route::prefix('destinations/{destination}')->group(function () {
        Route::get('like', [DestinationController::class, 'index'])->name('destinations.liked');
        Route::post('like', [DestinationController::class, 'like'])->name('destinations.like');
        Route::delete('unlike', [DestinationController::class, 'unlike'])->name('destinations.unlike');
    });

    // Review routes
    Route::prefix('destinations/{destination}')->group(function () {
        Route::post('reviews', [ReviewController::class, 'store'])->name('reviews.store');
    });
});

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

    // Review routes
    Route::prefix('destinasi/{destination}')->group(function () {
        Route::delete('reviews/{review}', [ReviewController::class, 'destroy'])->name('destinations.reviews.destroy');
    });

    // hapus foto Destinasi
    Route::delete('destinasi/{destination}/image/{image}', [AdminDestinationController::class, 'destroyImage'])->name('destinations.image.destroy');

    // kategori route
    Route::resource('kategori', CategoryController::class)
        ->parameters(['kategori' => 'category'])
        ->names('categories');

    // Pengajuan destinasi
    Route::prefix('pengajuan-destinasi')->name('destination-submission.')->group(function () {
        Route::get('/', [AdminDestinationSubmissionController::class, 'index'])->name('index');
        Route::get('/{destinationSubmission}', [AdminDestinationSubmissionController::class, 'edit'])->name('edit');
        Route::delete('/{destinationSubmission}', [AdminDestinationSubmissionController::class, 'destroy'])->name('destroy');
        Route::post('/{destinationSubmission}/approve', [AdminDestinationSubmissionController::class, 'approve'])->name('approve');
        Route::post('/{destinationSubmission}/reject', [AdminDestinationSubmissionController::class, 'reject'])->name('reject');
    });
});
