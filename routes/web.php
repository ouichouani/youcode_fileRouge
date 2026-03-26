<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/' , [DashboardController::class , 'index']);
Route::get('/dashboard' , [DashboardController::class , 'index']);
Route::resource('/tasks' , TaskController::class);
Route::resource('/categories' , CategoryController::class);
Route::resource('/users' , UserController::class);
Route::resource('/tasks' , TaskController::class);
Route::resource('/posts' , PostController::class);

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [UserController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [UserController::class, 'login']);
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');
    Route::resource('users', UserController::class);
});
