<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PostPolicy
{



    public function view(User $user, Post $post): bool
    {
        if($post->visibility === 'public') return true;
        if($user->role === 'Admin' || $user->role === 'Moderator') return true ;
        if($post->visibility === 'private' &&  $post->user_id != $user->id ) return false ;
        if($post->visibility === 'friends' && $user->is_frend_with($post->user)) return true ;

        return false;
    }


    public function update(User $user, Post $post): bool
    {
        if($post->user_id === $user->id || $user->role === 'Admin' || $user->role === 'Moderator') {
            return true;
        }
        return false;
    }


    public function delete(User $user, Post $post): bool
    {
        // if($post->user_id === $user->id || $user->role === 'Admin' || $user->role === 'Moderator' ) {
        if($post->user_id === $user->id || $user->role === 'Admin' ) {
            return true;
        }
        return false;
    }

    public function hide(User $user , Post $post): bool
    {
        if($post->user_id == $user->id) return false ;
        if($post->user->role == 'Admin') return false ;
        if($user->role === 'Admin' || $user->role === 'Moderator' ) {
            return true;
        }
        return false;
    }
}
