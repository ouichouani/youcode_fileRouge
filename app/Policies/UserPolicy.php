<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{

    public function update(User $user, User $model): bool
    {
        if($user->id === $model->id) return true ;
        return false;
    }

    public function delete(User $user, User $model): bool
    {
        if($user->id === $model->id) return true ;
        if($user->role === 'Admin') return true ;
        return false;
    }

    public function ban(User $user): bool
    {
        if($user->role === 'Admin') return true ;
        return false;
    }

    public function temp_ban (User $user):bool
    {
        if($user->role === 'Admin' || $user->role === 'Moderator') return true ;
        return false;
    }

    public function index(User $user): bool
    {
        if($user->role === 'Admin' || $user->role == 'Moderator') return true ;
        return false;
    }

    public function manage_app(User $user) : bool
    {
        if ($user->role == 'Admin' || $user->role == 'Moderator' ) return true ;
        return false ;
    }
    
}
