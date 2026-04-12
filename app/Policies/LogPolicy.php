<?php

namespace App\Policies;

use App\Models\User;
use App\Models\log;
use App\Models\Task;
use Illuminate\Auth\Access\Response;

class LogPolicy
{

    public function view(User $user, log $log): bool
    {
        if($user->id === $log->task->user_id){
            return true;
        }
        return false;
    }

    public function create(User $user , Task $task): bool
    {
        if($user->id === $task->user_id){
            return true;
        }
        return false;
    }


    public function delete(User $user, log $log): bool
    {
        if($user->id === $log->task->user_id){
            return true;
        }
        return false;
    }

}
