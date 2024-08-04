<?php

use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\PeriodController;
use App\Http\Controllers\SubjectController;
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
    // Route::resource('groups.users', UserGroupController::class)->scoped();
    Route::get('groups/{group}/users', [UserGroupController::class, 'index'])->scopeBindings()->name('groups.users.index');
    Route::post('groups/{group}/users', [UserGroupController::class, 'store'])->scopeBindings()->name('groups.users.store');
    Route::delete('groups/{group}/users/{user}', [UserGroupController::class, 'destroy'])
        ->scopeBindings()->whereUuid(['school', 'group', 'user']);
    Route::resource('subjects', SubjectController::class)->whereUuid('subject');
    Route::resource('periods/{period}/classrooms', ClassroomController::class)->whereUuid(['period', 'classroom'])->scoped();
    Route::resource('classrooms/{classroom}/assignments', AssignmentController::class)->whereUuid(['classroom', 'assignment'])->scoped();
});

Route::fallback(function () {
    return view('errors.404');
});
