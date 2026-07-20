<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BookingController;
use App\Models\Product;
use App\Models\User;

// HOME
Route::get('/', [DashboardController::class, 'index']);
Route::get('/rooms', [DashboardController::class, 'rooms'])->name('rooms.index');

// AUTH
Route::get('/register', function () {
    return view('register_new');
});

Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', function () {
    return view('login_new');
})->name('login');

Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');
Route::get('/profile', [AuthController::class, 'profile'])->middleware('auth');
Route::put('/profile', [AuthController::class, 'updateProfile'])->middleware('auth');

// DASHBOARD - Redirect ke home (booking dashboard)
Route::get('/dashboard', function () {
    return redirect('/');
})->middleware('auth');

// ================= BOOKING RUANG RAPAT =================
Route::get('/bookings', [BookingController::class, 'index'])->middleware('auth')->name('bookings.index');
Route::get('/bookings/create', [BookingController::class, 'create'])->middleware('auth')->name('bookings.create');
Route::post('/bookings', [BookingController::class, 'store'])->middleware('auth')->name('bookings.store');
Route::get('/bookings/{booking}/edit', [BookingController::class, 'edit'])->middleware('auth')->name('bookings.edit');
Route::get('/bookings/{booking}/activity', [BookingController::class, 'showActivity'])->middleware('auth')->name('bookings.activity');
Route::post('/bookings/{booking}/activity', [BookingController::class, 'storeActivity'])->middleware('auth')->name('bookings.activity.store');
Route::delete('/bookings/{booking}/activity', [BookingController::class, 'deleteActivity'])->middleware('auth')->name('bookings.activity.delete');
Route::put('/bookings/{booking}', [BookingController::class, 'update'])->middleware('auth')->name('bookings.update');
Route::delete('/bookings/{booking}', [BookingController::class, 'destroy'])->middleware('auth')->name('bookings.destroy');

// ================= PRODUK =================

// SEMUA USER BISA LIHAT
Route::get('/products', [ProductController::class, 'index'])->middleware('auth');

// HANYA ADMIN
Route::get('/products/create', [ProductController::class, 'create'])->middleware(['auth', 'role:admin']);
Route::post('/products', [ProductController::class, 'store'])->middleware(['auth', 'role:admin']);
Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->middleware(['auth', 'role:admin']);
Route::put('/products/{id}', [ProductController::class, 'update'])->middleware(['auth', 'role:admin']);
Route::delete('/products/{id}', [ProductController::class, 'destroy'])->middleware(['auth', 'role:admin']);

// DETAIL
Route::get('/products/{id}', [ProductController::class, 'show'])->middleware('auth');

// ADMIN routes (user management, calendar, activity logs, backup)
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users.index');
    Route::put('/admin/users/{user}', [AdminController::class, 'updateUser'])->name('admin.users.update');
    Route::get('/admin/calendar', [AdminController::class, 'calendar'])->name('admin.calendar');
    Route::get('/admin/activity-logs', [AdminController::class, 'activityLogs'])->name('admin.activity-logs');
    Route::get('/admin/backup', [AdminController::class, 'backup'])->name('admin.backup');
    Route::get('/bookings/export', [AdminController::class, 'backup'])->name('bookings.export');
});
