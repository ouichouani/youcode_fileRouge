<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FriendRequestController;
use App\Http\Controllers\HabitController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


// TO LEARN ABOUT //     ...->parameters(['tasks' => 'task'])->whereNumber('task'); // enforce numeric IDs

//user routes
Route::get('/register', [UserController::class, 'create'])->name('register');
Route::post('/register', [UserController::class, 'register']);
Route::get('/login', [UserController::class, 'showLoginForm'])->name('login');
Route::post('/login', [UserController::class, 'login']);

Route::middleware('auth')->group(function () {

    Route::get('/', [DashboardController::class, 'index']);
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard') ;

    Route::post('/logout', [UserController::class, 'logout'])->name('logout');
    Route::get('/profile', [UserController::class, 'profile'])->name('users.profile')->missing(function(){return redirect()->route('login')->with('message' ,'resource not found') ;});
    
    Route::resource('/habits', HabitController::class)->missing(function(){return redirect()->route('habits.index')->with('message' ,'resource not found') ;});
    Route::resource('/tasks', TaskController::class)->missing(function(){return redirect()->route('tasks.index')->with('message' ,'resource not found') ;});
    Route::resource('/categories', CategoryController::class)->missing(function(){return redirect()->route('tasks.index')->with('message' ,'resource not found') ;});
    Route::resource('/users', UserController::class)->missing(function(){return redirect()->route('users.profile')->with('message' ,'resource not found') ;});
    Route::resource('/posts', PostController::class)->missing(function(){return redirect()->route('posts.index')->with('message' ,'resource not found') ;});
    Route::resource('/requests', FriendRequestController::class)->missing(function(){return redirect()->route('requests.index')->with('message' ,'resource not found') ;});
   
    Route::post('/requests/{friendRequest}/accept', [FriendRequestController::class, 'accept'])->name('requests.accept')->missing(function(){return redirect()->route('requests.index')->with('message' ,'resource not found') ;});
    Route::post('/requests/{friendRequest}/reject', [FriendRequestController::class, 'reject'])->name('requests.reject')->missing(function(){return redirect()->route('requests.index')->with('message' ,'resource not found') ;});
   
    Route::post('/comments', [CommentController::class , 'store'])->name('comments.store')->missing(function(){return redirect()->route('posts.index')->with('message' ,'resource not found') ;});
    Route::delete('/comments/{comment}', [CommentController::class , 'destroy'])->name('comments.destroy')->missing(function(){return redirect()->route('posts.index')->with('message' ,'resource not found') ;});
    
    Route::post('/likes', [LikeController::class, 'save'])->name('likes.save')->missing(function(){return redirect()->route('posts.index')->with('message' ,'resource not found') ;});
    Route::post('/tasks/done', [LogController::class, 'store'])->name('logs.store')->missing(function(){return redirect()->route('dashboard')->with('message' ,'resource not found') ;}) ;
    Route::delete('/tasks/{log}/destroy', [LogController::class, 'destroy'])->name('logs.destroy')->missing(function(){return redirect()->route('dashboard')->with('message' ,'resource not found') ;}) ;
        
    
    });
    
    // Route::middleware('guest')->group(function () {
    // });


Route::fallback(function(){return view('components.notFound') ;}) ;
Route::get('/s' , [LogController::class , 'index']) ;