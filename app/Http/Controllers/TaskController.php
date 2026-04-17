<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Category;
use App\Models\Task;
use Illuminate\Support\Facades\Auth ;

class TaskController extends Controller
{


    public function tasks()
    {
        $tasks = Task::where('is_task' , true)->where('user_id' , Auth::id())->latest()->get() ;
        return view('tasks.tasks.index' , compact('tasks')) ;
    }

    public function habits()
    {
        $habits = Task::where('is_task' , false)->where('user_id' , Auth::id())->latest()->get() ;
        return view('tasks.habits.index' , compact('habits')) ;
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
        $this->authorize('view' , $task) ; 
        if(!$task->is_task) return redirect()->route('tasks.index')->with('message' ,'resource not found') ;
        return view('tasks.tasks.show' , compact('task')) ;
    }


    public function edit(Task $task)
    {
        $this->authorize('update' , $task) ;
        $user = Auth::user() ;
        $categories = Category::where('user_id' , $user->id)->get() ;
        return view('tasks.tasks.edit' , compact('task' , 'categories')) ;
    }


    public function update(UpdateTaskRequest $request, Task $task)
    {
        $this->authorize('update' , $task) ;
        $newTask = $request->validated() ;
        $task->update($newTask) ;
        $task->save() ;
        return  redirect()->route('tasks.show' , $task->id) ;

    }


    public function destroy(Task $task)
    {
        $this->authorize('delete' , $task) ;
        $task->delete() ;
        return redirect()->route('tasks.index') ;

    }

}
