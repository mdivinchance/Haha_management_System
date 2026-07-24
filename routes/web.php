<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DailyReportController;
use App\Http\Controllers\MoneyReportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth', 'role:super_admin,manager'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
    Route::patch('products/{product}/adjust-stock', [ProductController::class, 'adjustStock'])->name('products.adjust-stock');
    Route::get('products/{product}/reports/create', [DailyReportController::class, 'create'])->name('daily-reports.create');
    Route::post('products/{product}/reports', [DailyReportController::class, 'store'])->name('daily-reports.store');
    Route::get('products/{product}/reports/{dailyReport}/edit', [DailyReportController::class, 'edit'])->name('daily-reports.edit');
    Route::patch('products/{product}/reports/{dailyReport}', [DailyReportController::class, 'update'])->name('daily-reports.update');
    Route::delete('products/{product}/reports/{dailyReport}', [DailyReportController::class, 'destroy'])->name('daily-reports.destroy');

    Route::get('money-report', [MoneyReportController::class, 'index'])->name('money-report.index');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:super_admin'])->group(function () {
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('users', [UserController::class, 'store'])->name('users.store');
    Route::get('users/{user}', [UserController::class, 'edit'])->name('users.edit');
    Route::patch('users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('users/{user}', [UserController::class, 'deactivate'])->name('users.deactivate');
    Route::patch('users/{user}/activate', [UserController::class, 'activate'])->name('users.activate');
});

require __DIR__.'/auth.php';
