<?php

namespace App\Policies;

use App\Models\Report;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ReportPolicy
{


    public function viewAny(User $user): bool
    {
        if($user->role === 'Admin' || $user->role === 'Moderator') return true ;
        return false;
    }

    public function store (User $user):bool
    {
        if($user->role === 'Client') return true ;
        return false ;
    }

    public function view(User $user, Report $report): bool
    {
        if($user->role === 'Admin' || $user->role === 'Moderator') return true ;
        if($user->id === $report->user_id) return true ;
        return false;
    }

    public function update(User $user, Report $report): bool
    {
        if($user->id === $report->user_id) return true ;
        return false;
    }


    public function delete(User $user, Report $report): bool
    {
        if($user->id === $report->user_id) return true ;
        if($user->role === 'Admin' || $user->role === 'Moderator') return true ;
        return false;
    }

    public function confirm(User $user){
        if($user->role === 'Admin' || $user->role === 'Moderator') return true ;
        return false ;
    }

}
