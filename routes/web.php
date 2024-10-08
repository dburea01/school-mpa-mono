<?php

use App\Http\Controllers\AppreciationController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\PeriodController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserGroupController;
use App\Http\Controllers\WorkController;
use App\Http\Controllers\WorkTypeController;
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
    Route::get('users-by-name-and-role', [UserController::class, 'getUsersByNameAndRole'])->name('getUsersByNameAndRole');

    Route::get('/potential-duplicated-user', [UserController::class, 'potentialDuplicatedUser'])->name('potentialDuplicatedUser');

    Route::resource('periods', PeriodController::class);
    Route::resource('groups', GroupController::class);
    Route::get('groups/{group}/users', [UserGroupController::class, 'index'])->scopeBindings()->name('groups.users.index');
    Route::post('groups/{group}/users', [UserGroupController::class, 'store'])->scopeBindings()->name('groups.users.store');
    Route::delete('groups/{group}/users/{user}', [UserGroupController::class, 'destroy'])
        ->scopeBindings()->whereUuid(['school', 'group', 'user']);
    Route::resource('subjects', SubjectController::class)->whereUuid('subject');
    Route::resource('appreciations', AppreciationController::class)->whereNumber('appreciation');
    Route::resource('periods/{period}/classrooms', ClassroomController::class)->whereUuid(['period', 'classroom'])->scoped();
    
    Route::resource('classrooms/{classroom}/assignments', AssignmentController::class)->whereUuid(['classroom', 'assignment'])->scoped();
    Route::resource('work-types', WorkTypeController::class)->whereNumber('work_type');

    Route::resource('periods/{period}/works', WorkController::class)->whereUuid(['period', 'work']);
    Route::resource('works/{work}/results', ResultController::class)->whereUuid('work')->whereNumber('result');

    Route::get('users/{user}/results', [ResultController::class, 'resultsByUser'])->whereUuid('user')->name('resultsByUser');
});

Route::fallback(function () {
    return view('errors.404');
});
