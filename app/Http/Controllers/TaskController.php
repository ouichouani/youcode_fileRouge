<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\Models\User;
use Illuminate\Container\Attributes\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $user = Auth::user();
        // $tasks = Task::where('user_id' , $user->id)->get() ;
        
        $user = User::find(2);
        $tasks = Task::query()->orderBy('id')->get() ;
        return view('tasks.tasks.index' , compact('user' , 'tasks')) ;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = User::with('categories')->find(1) ;
        $categories = $user->categories ;
        return view('tasks.tasks.create' , compact('user' , 'categories') ) ;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        $data  = $request->validated() ;
        $data['user_id'] = 1 ;
        $task = Task::create($data) ;
        return $this->show($task) ;
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        return view('tasks.tasks.show' , compact('task')) ;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        $user = User::with('categories')->find(1) ;
        $categories = $user->categories ;
        return view('tasks.tasks.edit' , compact('task' , 'categories')) ;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {

        $newTask = $request->validated() ;
        $task->update($newTask) ;
        $task->save() ;
        return view('tasks.tasks.show' , compact('task')) ;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete() ;
        return $this->index() ;

    }
}
