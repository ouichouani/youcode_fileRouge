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

        $categories = Category::where(
            function ($q) use ($user) {
                $q->where('user_id', $user->id)->orWhere('is_global', true);
            }
        )->whereHas('tasks', function ($q) use ($user) {
            $q->where('user_id', $user->id) ;
        })

        ->with(['tasks' => function ($q) use ($user) {
                $q->where('user_id', $user->id)->orderBy('done');
        }])
        ->orderBy('id')
        ->get();

        $abandoned_tasks = Task::where('user_id' , $user->id)->where('category_id' , null)->where('is_task' , true)->get() ;

        return view('tasks.tasks.index', compact('user', "categories" , "abandoned_tasks"));
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
        $data['is_task'] = true ;
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


    public function done (Task $task){

        $this->authorize('update' , $task) ;
        $task->done = !$task->done ;
        $task->save() ;
        // return redirect()->route('tasks.index') ;
        return redirect()->back() ;

    }

}
