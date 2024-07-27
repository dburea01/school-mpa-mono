<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\PeriodController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserGroupController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', [AuthController::class, 'login'])->name('login');

/*
|--------------------------------------------------------------------------
| routes with authentication
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
    Route::resource('users', UserController::class);

    // Route::get('/find-duplicated-users', [UserController::class, 'findDuplicatedUser'])->name('getDuplicatedUsers');
    Route::get('/potential-duplicated-user', [UserController::class, 'potentialDuplicatedUser'])->name('potentialDuplicatedUser');

    Route::resource('periods', PeriodController::class);
    Route::resource('groups', GroupController::class);
    Route::resource('groups.users', UserGroupController::class)->scoped()->only(['index', 'create', 'destroy']);
});

Route::fallback(function () {
    return view('errors.404');
});
