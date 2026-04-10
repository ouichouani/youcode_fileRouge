<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Category;
use App\Models\Task;
use Illuminate\Support\Facades\Auth ;

class TaskController extends Controller
{


    public function index()
    {
        $user = Auth::user();
        $user->load('tasks') ;
        $tasks = $user->tasks ;
        return view('tasks.tasks.index' , compact('user' , 'tasks')) ;
    }


    public function create()
    {
        $user = Auth::user() ;
        $categories = Category::where('user_id' , $user->id)->get() ;
        return view('tasks.tasks.create' , compact('user' , 'categories') ) ;
    }

    public function store(StoreTaskRequest $request)
    {
        $data  = $request->validated() ;
        $data['user_id'] = Auth::id() ;
        $data['is_task'] = true;
        $data['frequency'] = ['OneTime'] ;
        $task = Task::create($data) ;
        return  redirect()->route('tasks.show' , $task->id) ;
    }

    
    public function show(Task $task)
    {
        if(!$task->is_task) return redirect()->route('tasks.index')->with('message' ,'resource not found') ;
        return view('tasks.tasks.show' , compact('task')) ;
    }


    public function edit(Task $task)
    {
        $user = Auth::user() ;
        $categories = Category::where('user_id' , $user->id)->get() ;
        return view('tasks.tasks.edit' , compact('task' , 'categories')) ;
    }


    public function update(UpdateTaskRequest $request, Task $task)
    {
        $newTask = $request->validated() ;
        $task->update($newTask) ;
        $task->save() ;
        return  redirect()->route('tasks.show' , $task->id) ;

    }


    public function destroy(Task $task)
    {
        $task->delete() ;
        return redirect()->route('tasks.index') ;

    }

}
