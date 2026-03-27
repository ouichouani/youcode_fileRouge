<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {

        $user = Auth::user() ;

        $user->load([
            'habits.category',
            'tasks.category'
        ]);

        $habits = $user?->habits;
        $tasks = $user?->tasks;

        return view("dashboard.dashboard", compact('user', 'tasks', 'habits'));
    }
}
