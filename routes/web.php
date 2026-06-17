<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

Route::get(
    '/login',
    [AuthController::class, 'showLogin']
)->name('login');

Route::post(
    '/login',
    [AuthController::class, 'login']
);

Route::middleware('auth')->group(function () {

    Route::post(
        '/logout',
        [AuthController::class, 'logout']
    );

    Route::get(
        '/dashboard',
        [DashboardController::class, 'index']
    );
});

Route::middleware([
    'auth',
    'role:admin'
])->group(function () {

    Route::get(
        '/admin/dashboard',
        [DashboardController::class, 'admin']
<<<<<<< Updated upstream
    );
=======
    )->name('admin.dashboard');

    Route::get('/admin/bookings', [BookingManagementController::class, 'adminIndex'])->name('admin.bookings.index');
    Route::patch('/admin/bookings/{id}/approve', [BookingManagementController::class, 'approve'])->name('admin.bookings.approve');
    Route::patch('/admin/bookings/{id}/reject', [BookingManagementController::class, 'reject'])->name('admin.bookings.reject');
    Route::resource(
        'admin/rooms',
        RoomController::class
    );
});

Route::middleware('auth')->group(function () {

    Route::get(
        '/bookings/create',
        [BookingController::class, 'create']
    )->name('bookings.create');

    Route::post(
        '/bookings',
        [BookingController::class, 'store']
    )->name('bookings.store');

    Route::get(
        '/bookings',
        [BookingManagementController::class, 'history']
    )->name('bookings.history');

    Route::patch(
        '/bookings/{id}/cancel',
        [BookingManagementController::class, 'cancel']
    )->name('bookings.cancel');
>>>>>>> Stashed changes
});