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

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application.
| These routes are loaded by the RouteServiceProvider and assigned to the
| "web" middleware group. Make something great!
|
*/

// login manual
// Auth::login(User::find(1));

// Auth routes - with guest middleware
Route::middleware('guest')->group(function () {
    Route::name('auth.')->group(function () {
        Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AuthController::class, 'login'])->name('login.post');
        Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
        Route::post('/register', [AuthController::class, 'register'])->name('register.post');
    });
});

// Logout route - with auth middleware
Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('user.home');
Route::get('/tentang', function () {
    return view('user.about');
})->name('user.about');

// Destination Public Routes
Route::match(['get', 'post'], 'destinasi', [DestinationController::class, 'index'])
    ->name('user.destinations.index');

Route::resource('destinasi', DestinationController::class)
    ->only(['show'])
    ->parameters(['destinasi' => 'destination:slug'])
    ->names(['show' => 'user.destinations.show']);


// User routes - with auth.user middleware
Route::middleware('auth.user')->name('user.')->group(function () {
    // Profile Routes
    Route::prefix('profil')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('show');
        Route::patch('/{user}', [ProfileController::class, 'update'])->name('update');
    });

    // Itinerary Routes
    Route::prefix('rencana-perjalanan')->name('itinerary.')->group(function () {
        // Main Itinerary Routes
        Route::get('/', [ItineraryController::class, 'index'])->name('index');
        Route::get('/tambah-rencana', [ItineraryController::class, 'create'])->name('create');
        Route::post('/', [ItineraryController::class, 'store'])->name('store');
        Route::get('/{itinerary}', [ItineraryController::class, 'show'])->name('show');
        Route::get('/{itinerary}/ubah', [ItineraryController::class, 'edit'])->name('edit');
        Route::patch('/{itinerary}', [ItineraryController::class, 'update'])->name('update');
        Route::delete('/{itinerary}', [ItineraryController::class, 'destroy'])->name('destroy');

        // Itinerary Destination Routes
        Route::post('/cari-destinasi-koordinat', [ItineraryController::class, 'searchDestinationsByCoordinates'])->name('destination.search.coordinates');
        Route::post('/cari-destinasi-nama', [ItineraryController::class, 'searchDestinationsByName'])->name('destination.search.name');
        Route::post('/tambah-destinasi', [ItineraryController::class, 'addDestinationItinerary'])->name('destination.add');
        Route::post('/hapus-destinasi', [ItineraryController::class, 'removeDestinationFromItinerary'])->name('destination.remove');
        Route::get('/destinasi/{id}/detail', [ItineraryController::class, 'getDestinationDetails'])->name('destination.detail');
        Route::post('/destinasi/update', [ItineraryController::class, 'updateDestination'])->name('destination.update');
    });

    // Destination Submission Routes
    Route::prefix('pengajuan-destinasi')->name('destination-submission.')->group(function () {
        Route::get('/', [DestinationSubmissionController::class, 'create'])->name('create');
        Route::post('/', [DestinationSubmissionController::class, 'store'])->name('store');
    });

    // Favorite Routes
    Route::prefix('destinasi-favorit')->name('destination-favorite.')->group(function () {
        Route::get('/', [FavoriteController::class, 'index'])->name('index');
    });

    // Destination & Review Routes
    Route::group(['prefix' => 'destinasi/{destination}'], function () {
        // Like/Unlike Routes
        Route::post('like', [DestinationController::class, 'like'])->name('destinations.like');
        Route::delete('unlike', [DestinationController::class, 'unlike'])->name('destinations.unlike');

        // Review Routes
        Route::post('reviews', [ReviewController::class, 'store'])->name('reviews.store');
    });
});

// Admin routes - with auth.admin middleware
Route::middleware('auth.admin')->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // User Management
    Route::resource('pengguna', UserController::class)
        ->parameters(['pengguna' => 'user'])
        ->names('users');

    // Destination Management
    Route::resource('destinasi', AdminDestinationController::class)
        ->parameters(['destinasi' => 'destination'])
        ->names('destinations');

    // Delete Destination Image
    Route::delete('destinasi/{destination}/image/{image}', [AdminDestinationController::class, 'destroyImage'])
        ->name('destinations.image.destroy');

    // Review Management
    Route::delete('destinasi/{destination}/reviews/{review}', [ReviewController::class, 'destroy'])
        ->name('destinations.reviews.destroy');

    // Category Management
    Route::resource('kategori', CategoryController::class)
        ->parameters(['kategori' => 'category'])
        ->names('categories');

    // Destination Submission Management
    Route::prefix('pengajuan-destinasi')->name('destination-submission.')->group(function () {
        Route::get('/', [AdminDestinationSubmissionController::class, 'index'])->name('index');
        Route::get('/{destinationSubmission}', [AdminDestinationSubmissionController::class, 'edit'])->name('edit');
        Route::delete('/{destinationSubmission}', [AdminDestinationSubmissionController::class, 'destroy'])->name('destroy');
        Route::post('/{destinationSubmission}/approve', [AdminDestinationSubmissionController::class, 'approve'])->name('approve');
        Route::post('/{destinationSubmission}/reject', [AdminDestinationSubmissionController::class, 'reject'])->name('reject');
    });
});
