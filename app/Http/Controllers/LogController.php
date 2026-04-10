<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorelogRequest;
use App\Models\Log;
use Illuminate\Support\Facades\Auth;

class LogController extends Controller
{

    public function index()
    {
        // show the history of the task
    }


    public function store(StorelogRequest $request)
    {
        // now()->toDateString();

        // check if the task is already done for today
        $existingLog = Log::where('task_id', $request->task_id)
            ->where('user_id', Auth::id())
            ->where('completed_date', now()->toDateString())
            ->first();

        if ($existingLog) {
            return redirect()->back()->with('message', 'Task already marked as done for today');
        }

        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $data['completed_date'] = now()->toDateString();
        $log = Log::create($data);
        return redirect()->back()->with('message', 'log created successfully');
    }


    public function destroy(Log $log)
    {
        $log->delete();
        return redirect()->back()->with('message', 'log deleted successfully');
    }
}
