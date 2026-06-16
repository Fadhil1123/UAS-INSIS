<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BookingManagementController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\BookingController;

Route::middleware('guest')->group(function () {

    Route::get(
        '/login',
        [AuthController::class, 'showLogin']
    )->name('login');

    Route::post(
        '/login',
        [AuthController::class, 'login']
    );
});

Route::middleware('auth')->group(function () {

    Route::post(
        '/logout',
        [AuthController::class, 'logout']
    );

    Route::get(
        '/dashboard',
        [DashboardController::class, 'index']
    );

    Route::get(
        '/bookings/create',
        [BookingController::class, 'create']
    )->name('bookings.create');

    Route::post(
        '/bookings',
        [BookingController::class, 'store']
    )->name('bookings.store');
});

Route::middleware([
    'auth',
    'role:admin'
])->group(function () {

    Route::get(
        '/admin/dashboard',
        [DashboardController::class, 'admin']
    );

    Route::get('/admin/bookings', [BookingManagementController::class, 'adminIndex'])->name('admin.bookings.index');
    Route::patch('/admin/bookings/{id}/approve', [BookingManagementController::class, 'approve'])->name('admin.bookings.approve');
    Route::patch('/admin/bookings/{id}/reject', [BookingManagementController::class, 'reject'])->name('admin.bookings.reject');
    Route::resource(
        'admin/rooms',
        RoomController::class
    );
});