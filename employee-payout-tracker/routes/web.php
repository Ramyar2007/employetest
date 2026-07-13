<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PayoutController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('payouts.index'));

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // Payouts — index/create/store open to any authenticated user (admin + staff).
    Route::get('/payouts', [PayoutController::class, 'index'])->name('payouts.index');
    Route::get('/payouts/create', [PayoutController::class, 'create'])->name('payouts.create');
    Route::post('/payouts', [PayoutController::class, 'store'])->name('payouts.store');

    // Payouts — edit, status change, delete are admin-only, enforced server-side.
    Route::middleware('role:admin')->group(function () {
        Route::get('/payouts/{payout}/edit', [PayoutController::class, 'edit'])->name('payouts.edit');
        Route::put('/payouts/{payout}', [PayoutController::class, 'update'])->name('payouts.update');
        Route::patch('/payouts/{payout}/status', [PayoutController::class, 'updateStatus'])->name('payouts.status');
        Route::delete('/payouts/{payout}', [PayoutController::class, 'destroy'])->name('payouts.destroy');

        // Employees — fully admin-only.
        Route::resource('employees', EmployeeController::class)->except(['show']);
    });
});
