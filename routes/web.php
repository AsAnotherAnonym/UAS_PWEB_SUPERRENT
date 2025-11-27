
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\LokasiRentalController as AdminLokasiRentalController;
use App\Http\Controllers\Admin\MotorController as AdminMotorController;
use App\Http\Controllers\Admin\UnitMotorController as AdminUnitMotorController;
use App\Http\Controllers\Admin\PenyewaanController as AdminPenyewaanController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Karyawan\DashboardController as KaryawanDashboardController;
use App\Http\Controllers\Karyawan\PenyewaanController as KaryawanPenyewaanController;
use App\Http\Controllers\Karyawan\UnitMotorController as KaryawanUnitMotorController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\LokasiRentalController as UserLokasiRentalController;
use App\Http\Controllers\User\PenyewaanController as UserPenyewaanController;
use App\Http\Controllers\User\ReviewController as UserReviewController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
/*
Route::get('/', function () {
    return view('welcome');
});
*/

// File serving routes (must be authenticated)
Route::middleware('auth')->group(function () {
    Route::get('/files/users/{filename}', [App\Http\Controllers\FileController::class, 'serveUserPhoto'])->name('file.user');
    Route::get('/files/locations/{filename}', [App\Http\Controllers\FileController::class, 'serveLocationPhoto'])->name('file.location');
    Route::get('/files/units/{filename}', [App\Http\Controllers\FileController::class, 'serveUnitPhoto'])->name('file.unit');
});

// Guest Routes (Login & Register)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // User Management
    Route::resource('users', AdminUserController::class);
    
    // Lokasi Rental Management
    Route::resource('lokasi-rental', AdminLokasiRentalController::class);
    
    // Motor Management
    Route::resource('motor', AdminMotorController::class);
    
    // Unit Motor Management
    Route::resource('unit-motor', AdminUnitMotorController::class);
    
    // Penyewaan Management
    Route::resource('penyewaan', AdminPenyewaanController::class)->except(['create', 'store', 'edit', 'update']);
    Route::post('penyewaan/{penyewaan}/accept', [AdminPenyewaanController::class, 'accept'])->name('penyewaan.accept');
    Route::post('penyewaan/{penyewaan}/reject', [AdminPenyewaanController::class, 'reject'])->name('penyewaan.reject');
    Route::post('penyewaan/{penyewaan}/complete', [AdminPenyewaanController::class, 'complete'])->name('penyewaan.complete');
    
    // Reviews Management
    Route::resource('reviews', AdminReviewController::class)->only(['index', 'show', 'destroy']);
});

// Karyawan Routes
Route::middleware(['auth', 'role:karyawan'])->prefix('karyawan')->name('karyawan.')->group(function () {
    Route::get('/dashboard', [KaryawanDashboardController::class, 'index'])->name('dashboard');
    
    // Unit Motor Management (for assigned location only)
    Route::resource('unit-motor', KaryawanUnitMotorController::class);
    
    // Penyewaan Management (for assigned location only)
    Route::resource('penyewaan', KaryawanPenyewaanController::class)->only(['index', 'show']);
    Route::post('penyewaan/{penyewaan}/accept', [KaryawanPenyewaanController::class, 'accept'])->name('penyewaan.accept');
    Route::post('penyewaan/{penyewaan}/reject', [KaryawanPenyewaanController::class, 'reject'])->name('penyewaan.reject');
    Route::post('penyewaan/{penyewaan}/complete', [KaryawanPenyewaanController::class, 'complete'])->name('penyewaan.complete');
});

// User Routes
Route::middleware(['auth', 'role:user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    
    // Lokasi Rental (Browse & View)
    Route::get('/lokasi-rental', [UserLokasiRentalController::class, 'index'])->name('lokasi-rental.index');
    Route::get('/lokasi-rental/{lokasiRental}', [UserLokasiRentalController::class, 'show'])->name('lokasi-rental.show');
    
    // Penyewaan (Rental Requests)
    Route::resource('penyewaan', UserPenyewaanController::class)->except(['edit', 'update']);
    Route::post('penyewaan/{penyewaan}/cancel', [UserPenyewaanController::class, 'cancel'])->name('penyewaan.cancel');
    
    // Reviews
    Route::resource('reviews', UserReviewController::class);
});

// Default redirect based on role
Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isKaryawan()) {
            return redirect()->route('karyawan.dashboard');
        } else {
            return redirect()->route('user.dashboard');
        }
    }
    return redirect()->route('login');
})->name('home');