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

    
}
