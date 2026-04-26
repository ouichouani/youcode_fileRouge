<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ModeratorController extends UserController
{
    public function ban(User $user)
    {

        $this->authorize('temp_ban' , User::class );

        // add the constraint (moderator can ban only thoes who he did ban befor , not other's banned users) : required changes on users table or new table ;

        if ($user->is_banned_by_moderator) {
            $user->is_banned_by_moderator = false;
        } else {
            $user->is_banned_by_moderator = true;
        }
        $user->save();
        return redirect()->back()->with('message', 'User ' . ($user->is_banned_by_moderator ? 'banned' : 'unbanned') . ' successfully');
    }

    public function confirmReport(Report $report)
    {
        $this->authorize('confirm', $report);

        if ($report->is_confirmed) {
            $report->is_confirmed = false;
        } else {
            $report->is_confirmed = true;
        }

        $report->save();
        return redirect()->back()->with('message', 'Report ' . ($report->is_confirmed ? 'confirmed' : 'unconfirmed') . ' successfully');
    }

    public function hidePost(Post $post)
    {
        if (Auth::user()->role === 'Client') return redirect()->back()->with('error', 'You cannot hide a post. Please contact an admin to hide this post.');

        if ($post->is_hidden) {
            $post->is_hidden = false;
        } else {
            $post->is_hidden = true;
        }

        $post->save();
        return redirect()->back()->with('message', 'Post ' . ($post->is_hidden ? 'hidden' : 'unhidden') . ' successfully');
    }

    public function blackList()
    {
        $this->authorize('manage_app' , User::class);
        $users = null;

        if (Auth::user()->role != 'Admin') {
            $users = User::where('is_banned_by_moderator' , true)->where('is_banned' , false)->get();
        } else {
            $users = User::where('is_banned_by_moderator' , true)->orWhere('is_banned' , true)->get();
        }
        return view('users.users.index', compact('users'));
    }

    public function showHiddenPosts(){

        $this->authorize('manage_app' , User::class);
        $posts = Post::where('is_hidden' , true)->with(['user.image' , 'comments.user.image' , 'comments.post', 'likes', 'user.image', 'images', 'reports'])->latest()->get() ;
        return view('posts.index' , compact('posts')) ;  
        
    }
}
