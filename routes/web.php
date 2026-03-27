<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HabitController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;



Route::resource('/habits', HabitController::class)->missing(function(){return redirect()->route('habits.index')->with('message' ,'resource not found') ;});
Route::resource('/tasks', TaskController::class)->missing(function(){return redirect()->route('tasks.index')->with('message' ,'resource not found') ;});
Route::resource('/categories', CategoryController::class)->missing(function(){return redirect()->route('tasks.index')->with('message' ,'resource not found') ;});
Route::resource('/users', UserController::class)->missing(function(){return redirect()->route('users.profile')->with('message' ,'resource not found') ;});
Route::resource('/posts', PostController::class)->missing(function(){return redirect()->route('posts.index')->with('message' ,'resource not found') ;});

//user routes
Route::get('/register', [UserController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [UserController::class, 'register']);
Route::get('/login', [UserController::class, 'showLoginForm'])->name('login');
Route::post('/login', [UserController::class, 'login']);

Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index']);
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard') ;
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');
    Route::resource('users', UserController::class);
    });
    
    // Route::middleware('guest')->group(function () {
    // });


Route::fallback(function(){return view('components.notFound') ;}) ;