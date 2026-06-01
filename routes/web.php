<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\User\CutiController as UserCutiController;
use App\Http\Controllers\Manajer\CutiController as ManajerCutiController;
use App\Http\Controllers\Manajer\UserController as ManajerUserController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('login'));

// Auth routes (guest only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegister'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.post');
});

// Protected routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // User routes
    Route::middleware('role:user')->prefix('cuti')->name('user.cuti.')->group(function () {
        Route::get('/', [UserCutiController::class, 'index'])->name('index');
        Route::get('/ajukan', [UserCutiController::class, 'create'])->name('create');
        Route::post('/ajukan', [UserCutiController::class, 'store'])->name('store');
        Route::put('/{cuti}', [UserCutiController::class, 'update'])->name('update');
        Route::delete('/{cuti}', [UserCutiController::class, 'cancel'])->name('cancel');
    });

    // Manajer routes
    Route::middleware('role:manajer')->prefix('manajer')->name('manajer.')->group(function () {
        Route::prefix('cuti')->name('cuti.')->group(function () {
            Route::get('/', [ManajerCutiController::class, 'index'])->name('index');
            Route::post('/{cuti}/approve', [ManajerCutiController::class, 'approve'])->name('approve');
            Route::post('/{cuti}/reject', [ManajerCutiController::class, 'reject'])->name('reject');
        });

        Route::prefix('user')->name('user.')->group(function () {
            Route::get('/', [ManajerUserController::class, 'index'])->name('index');
            Route::post('/', [ManajerUserController::class, 'store'])->name('store');
            Route::post('/{user}/approve', [ManajerUserController::class, 'approve'])->name('approve');
            Route::post('/{user}/reject', [ManajerUserController::class, 'reject'])->name('reject');
            Route::put('/{user}', [ManajerUserController::class, 'update'])->name('update');
            Route::delete('/{user}', [ManajerUserController::class, 'destroy'])->name('destroy');
        });
    });
});
