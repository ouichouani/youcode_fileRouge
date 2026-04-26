<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use App\Models\Post;
use App\Models\Report;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdminController extends ModeratorController
{

    public function ban(User $user) 
    {
        $this->authorize('ban', $user);

        if($user->role == 'Admin'){
            return redirect()->route('admin.users.index')->with('message', "you can\'t ban $user->name bacause he\'s an admin");
        }

        if($user->is_banned || $user->is_banned_by_moderator ){
            $user->is_banned_by_moderator = false;
            $user->is_banned = false;
        }else{
            $user->is_banned = true;
        }
        $user->save();
        return redirect()->back()->with('message', 'User '.($user->is_banned ? 'banned' : 'unbanned').' successfully');
        // return redirect()->route('admin.users.index')->with('message', 'User '.($user->is_banned ? 'banned' : 'unbanned').' successfully');
    }
}


    // public function dashboard()
    // {
    //     $user = Auth::user();
    //     if(!$user->role == 'Admin') return redirect()->route('dashboard')->with('error', 'You do not have permission to access this page.');
        
    //     $users = User::all() ;
    //     $reports = Report::all();
    //     $posts = Post::where('is_hidden', true)->get();
    //     $global_categories = Category::where('is_global', true)->orWhere("user_id" , Auth::id())->get();

    //     $data = $this->loadDataForDashboard($user);
    //     $habits = $data['habits'];
    //     $tasks = $data['tasks'];
    //     return view('dashboard.dashboard', compact('user', 'habits', 'tasks', 'users', 'reports', 'posts' , 'global_categories'));
    // }