<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BookingManagementController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CalendarController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// ─── Authenticated Routes ──────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // Dashboard per role
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dosen/dashboard', [DashboardController::class, 'dosen'])->name('dosen.dashboard');

    // Ruangan
    Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');

    // Kalender
    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
    Route::get('/calendar/events', [CalendarController::class, 'getEvents'])->name('calendar.events');

    // Booking
    Route::get('/bookings', [BookingManagementController::class, 'history'])->name('bookings.history');
    Route::get('/bookings/create', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::patch('/bookings/{id}/cancel', [BookingManagementController::class, 'cancel'])->name('bookings.cancel');
    Route::get('/bookings/template-surat', [BookingController::class, 'downloadTemplate'])->name('bookings.template');
});

// ─── Admin-Only Routes ─────────────────────────────────────────────────────
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'admin'])->name('admin.dashboard');

    Route::get('/admin/bookings', [BookingManagementController::class, 'adminIndex'])->name('admin.bookings.index');
    Route::patch('/admin/bookings/{id}/approve', [BookingManagementController::class, 'approve'])->name('admin.bookings.approve');
    Route::patch('/admin/bookings/{id}/reject', [BookingManagementController::class, 'reject'])->name('admin.bookings.reject');

    Route::resource('admin/rooms', RoomController::class)->names([
        'index'   => 'admin.rooms.index',
        'create'  => 'admin.rooms.create',
        'store'   => 'admin.rooms.store',
        'show'    => 'admin.rooms.show',
        'edit'    => 'admin.rooms.edit',
        'update'  => 'admin.rooms.update',
        'destroy' => 'admin.rooms.destroy',
    ]);
});