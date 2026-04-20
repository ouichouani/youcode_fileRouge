<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\FriendRequestController;
use App\Http\Controllers\HabitController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\ModeratorController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


// TO LEARN ABOUT //     ...->parameters(['tasks' => 'task'])->whereNumber('task'); // enforce numeric IDs

//user routes
Route::get('/register', [UserController::class, 'create'])->name('register');
Route::post('/register', [UserController::class, 'register']);
Route::get('/login', [UserController::class, 'showLoginForm'])->name('login');
Route::post('/login', [UserController::class, 'login']);

Route::middleware('auth')->group(function () {

    Route::get('/', [UserController::class, 'dashboard']);
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');

    Route::post('/logout', [UserController::class, 'logout'])->name('logout');
    Route::get('/profile', [UserController::class, 'profile'])->name('users.profile') ;


    Route::resource('/habits', HabitController::class)->missing(function () {return redirect()->route('habits.index')->with('message', 'resource not found');});
    Route::resource('/tasks', TaskController::class)->missing(function () {return redirect()->route('tasks.index')->with('message', 'resource not found');});
    Route::resource('/categories', CategoryController::class)->missing(function () {return redirect()->route('tasks.index')->with('message', 'resource not found');});
    Route::resource('/users', UserController::class)->missing(function () {return redirect()->route('users.profile')->with('message', 'resource not found');});
    Route::resource('/posts', PostController::class)->missing(function () {return redirect()->route('posts.index')->with('message', 'resource not found');});
    Route::resource('/requests', FriendRequestController::class)->missing(function () {return redirect()->route('requests.index')->with('message', 'resource not found');});
    Route::resource('/reports', ReportController::class)->missing(function () {return redirect()->route('reports.index')->with('message', 'resource not found');});
    Route::resource('/notifications', NotificationController::class)->missing(function () {return redirect()->route('notification.index')->with('message', 'resource not found');});
    
    Route::post('/reports/{report}', [ModeratorController::class , 'confirmReport'])->name('reports.confirm')->missing(function () {return redirect()->route('reports.index')->with('message', 'resource not found');});
    Route::get('/tasks' , [TaskController::class , 'tasks'])->name('tasks.index') ;
    Route::get('/habits' , [TaskController::class , 'habits'])->name('habits.index') ;
    
    Route::get('/logs', [LogController::class , 'index'])->name('logs.index');

    Route::post('/requests/{friendRequest}/accept', [FriendRequestController::class, 'accept'])->name('requests.accept')->missing(function () {return redirect()->route('requests.index')->with('message', 'resource not found');});
    Route::post('/requests/{friendRequest}/reject', [FriendRequestController::class, 'reject'])->name('requests.reject')->missing(function () {return redirect()->route('requests.index')->with('message', 'resource not found');});

    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store')->missing(function () {return redirect()->route('posts.index')->with('message', 'resource not found');});
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy')->missing(function () {return redirect()->route('posts.index')->with('message', 'resource not found');});

    Route::post('/likes', [LikeController::class, 'save'])->name('likes.save');

    Route::post('/tasks/done', [LogController::class, 'store'])->name('logs.store')->missing(function () {return redirect()->route('dashboard')->with('message', 'resource not found');});
    Route::delete('/tasks/{log}/destroy', [LogController::class, 'destroy'])->name('logs.destroy')->missing(function () {return redirect()->route('dashboard')->with('message', 'resource not found');});

    route::group(['prefix' => 'moderator'], function () {
        Route::get('users', [ModeratorController::class, 'index'])->name('moderator.users.index') ;
        Route::post('users/{user}/ban', [ModeratorController::class, 'ban'])->name('moderator.users.ban')->missing(function () {return redirect()->route('moderator.dashboard')->with('message', 'resource not found');});
        Route::post('post/{post}/hide', [ModeratorController::class, 'hidePost'])->name('posts.hide');
    });
        
    route::group(['prefix' => 'admin'], function () {
        Route::get('users', [AdminController::class, 'index'])->name('admin.users.index') ;
        Route::post('users/{user}/ban', [AdminController::class, 'ban'])->name('admin.users.ban')->missing(function () {return redirect()->route('admin.dashboard')->with('message', 'resource not found');});
        Route::post('post/{post}/hide', [AdminController::class, 'hidePost'])->name('moderator.posts.hide');
    });

    route::group(['prefix' => 'controll-panel'] , function(){
        Route::get('/' , [ModeratorController::class , 'blackList'])->name('blackList') ;
        Route::get('black-list' , [ModeratorController::class , 'blackList'])->name('blackList') ;
        Route::get('posts/hidden' , [ModeratorController::class , 'showHiddenPosts'])->name('posts.hidden') ;
        route::get('global-categories' , [CategoryController::class , 'indexGlobalCategories'])->name('categories.global');
    });
});


Route::fallback(function () {return view('components.notFound');})->name('fallback');
Route::get('/s', [LogController::class, 'index']);

