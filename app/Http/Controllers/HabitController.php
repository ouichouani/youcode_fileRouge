<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreHabitRequest;
use App\Http\Requests\UpdateHabitRequest;

use App\Models\Category;
use App\Models\Task;
use App\Policies\TaskPolicy;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HabitController extends Controller
{


    public function index()
    {
        $user = Auth::user();

        $categories = Category::where(
            function ($q) use ($user) {
                $q->where('user_id', $user->id)->orWhere('is_global', true);
            }
        )->whereHas('habits', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })
            ->with(['habits' => function ($q) use ($user) {
                $q->where('user_id', $user->id);
        }])
        ->get();

        $abandoned_habits = Task::where('user_id' , $user->id)->where('category_id' , null)->where('is_task' , false)->get() ;
        // dd($abandoned_habits) ;


        return view('tasks.habits.index', compact('user', "categories" , "abandoned_habits"));
    }

    public function create()
    {
        $user = Auth::user();
        $categories = Category::where('user_id', $user->id)->get();
        return view('tasks.habits.create', compact('user', 'categories'));
    }


    public function store(StoreHabitRequest $request)
    {
        $data  = $request->validated();
        $data['user_id'] = Auth::id();
        $habit = Task::create($data);
        return redirect()->route('habits.show', $habit);
    }


    public function show(Task $habit)
    {
        if ($habit->is_task) return redirect()->route('habits.index')->with('message', 'resource not found');
        return view('tasks.habits.show', compact('habit'));
    }


    public function edit(Task $habit)
    {
        $this->authorize('update', $habit);
        $user = Auth::user();
        $categories = Category::where('user_id', $user->id)->get();
        return view('tasks.habits.edit', compact('habit', 'categories'));
    }

    public function update(UpdateHabitRequest $request, Task $task)
    {
        $this->authorize('update', $task);
        $newHabit = $request->validated();
        $task->update($newHabit);
        $task->save();
        return redirect()->route('habits.show', $task);
    }


    public function destroy(Task $habit)
    {
        $this->authorize('delete', $habit);
        $habit->delete();
        return redirect()->route('habits.index');
    }
}
