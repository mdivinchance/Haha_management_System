<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])
        ->name('password.request');

    Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLink'])
        ->name('password.email');

    Route::get('change-password', [ResetPasswordController::class, 'showChangeForm'])
        ->name('password.change-form');

    Route::post('change-password', [ResetPasswordController::class, 'changePassword'])
        ->name('password.change');
});

Route::middleware('auth')->group(function () {
    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});
