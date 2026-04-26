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

