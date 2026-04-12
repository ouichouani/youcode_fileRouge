<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CommentPolicy
{


    public function viewAny(User $user , Post $post): bool
    {
        if($post->visibility === 'public') return true;
        if($user->role === 'Admin' || $user->role === 'Moderator') return true ;
        if($post->visibility === 'private' &&  $post->user_id != $user->id ) return false ;
        if($post->visibility === 'friends' && $user->is_frend_with($post->user)) return true ;
        return false;
    }

    public function create(User $user , Post $post): bool
    {
        if($post->user_id === $user->id ) return true;
        if($post->visibility === 'public') return true;
        if($post->visibility === 'friends' && $user->is_frend_with($post->user)) return true ;
        return false;
    }

    public function delete(User $user, Comment $comment): bool
    {
        if($comment->user_id === $user->id ) return true ;
        if($user->role === 'Admin' || $user->role === 'Moderator' ) return true ;
        if($comment->post->user_id === $user->id) return true;
        return false;
    }
}
