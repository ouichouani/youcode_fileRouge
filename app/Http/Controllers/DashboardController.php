<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {

        $user = Auth::user() ;

        // select all user's habits and tasks with their categories and logs for the current month

        $user->load([
            'habits.category',
            'habits.logs' => function ($query) {
                $query->whereMonth('completed_date', now()->month)->whereYear('completed_date', now()->year)->orderBy('completed_date', 'asc');
            },
            'tasks.category' , 
        ]);


        

        $habits = $user?->habits;
        $tasks = $user?->tasks;

        return view("dashboard.dashboard", compact('user', 'tasks', 'habits'));
    }


}