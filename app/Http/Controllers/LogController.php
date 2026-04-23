<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorelogRequest;
use App\Models\Log;
use App\Models\Task;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class LogController extends Controller
{

    // decrement streaks when deleting a log

    public function index()
    {
        // show the history of the task
        $logs = Log::whereHas('task' , function($q){
            $q->where('user_id' , Auth::id()) ;
        })->with('task')->get() ;
        return view('tasks.logs.index' , compact('logs')) ;
    }


    public function store(StorelogRequest $request)
    {
        
        $habit = Task::where('id', $request->task_id)
        ->where('user_id', Auth::id())
        ->firstOrFail();
        
        $this->authorize('create', [Log::class, $habit]);

        // check if the task is already done for today
        $existingLog = Log::where('task_id', $habit->id)
            ->where('completed_date', now()->toDateString())
            ->first();

        if ($existingLog) {
            return redirect()->back()->with('message', 'Habit already marked as done for today');
        }

        $today = now()->format('l');
        if (!in_array($today, $habit->frequency)) {
            return redirect()->back()->with('message', 'Habit not suppose to be done today');
        }

        // streaks logic

        $lastLog = Log::where('task_id', $habit->id)
            ->orderBy('completed_date', 'desc')
            ->first();


        $data = $request->validated();
        $data['completed_date'] = now()->toDateString();
        Log::create($data);

        // update user score
        $this->IncrementStreaks($habit, $lastLog);
        $this->calculateScore();

        return redirect()->back()->with('message', 'log created successfully');
    }


    public function destroy(Log $log)
    {
        $this->authorize('delete', $log);
        
        // if user checked the log by mistake, he can delete it but and decrement the streaks if it was not the first day of the streaks
        $task = $log->task;
        if(!$log->completed_date->isToday()){ 
            return redirect()->back()->with('message', 'You can only delete today\'s log');
        }

        if ($task->streaks > 0) {
            $task->streaks -= 1;
            $task->save();
        }
        
        $log->delete();

        return redirect()->back()->with('message', 'log deleted successfully');
    }


    public function calculateScore()
    {

        // - WHEN THE TASK IS CREATED
        // - WHO MUSH DAY PASS SINCE THEN
        // - CALCULATE HOW MANY TIME THIS TASK WAS DONE
        // - BESNIS LOGIC : ((priority * difficulty) * total_days_sice_creation) - ((priority * difficulty) * total_days_sice_creation * 0.5)

        $user = Auth::user();

        // get all habits 
        $habits = Task::where('user_id', $user->id)
            ->where('is_task', false)
            ->withCount('logs')
            ->orderBy('created_at', 'desc')
            ->get();

        $difficultyMap = ['xxs' => 1, 'xs' => 2, 's' => 3, 'm' => 4, 'l' => 5, 'xl' => 6, 'xxl' => 7];
        $priorityMap = ['xxs' => 1, 'xs' => 2, 's' => 3, 'm' => 4, 'l' => 5, 'xl' => 6, 'xxl' => 7];


        //calculate total score
        $totalScore = $habits->map(function ($habit) use ($difficultyMap, $priorityMap) {

            $difficulty = $difficultyMap[$habit->difficulty];
            $priority = $priorityMap[$habit->priority];
            $totalLogs = $habit->logs_count;

            // get all expected days in case a habit is not daily
            $expectedDays = 1 ;
            if((int)$habit->created_at->diffInDays(now()) > 0 ){
                $expectedDays = collect(range(0, $habit->created_at->diffInDays(now())))
                ->map(fn($i) => $habit->created_at->copy()->addDays($i))
                ->filter(fn($date) => (in_array($date->format('l'), $habit->frequency)))
                ->count();
            }

            return ($difficulty * $priority * $totalLogs) - ($difficulty * $priority * $expectedDays * 0.5) + $habit->streaks;
        })->avg();


        $user->score = (int) $totalScore;
        $user->save();
    }

    public function IncrementStreaks(Task $habit, ?Log $lastLog)
    {

        if (!$habit) {
            return redirect()->back()->with('message', 'Habit not found');
        }

        if (!$lastLog) {
            $habit->streaks = 1;
            return $habit->save();
        }

        $today = now();
        $incremented = false;

        for ($i = 1; $i <= 7; $i++) {

            $prevDate = $today->copy()->subDays($i);
            if (in_array($prevDate->format('l'), $habit->frequency)) {

                if ($prevDate->isSameDay($lastLog->completed_date)) {
                    $incremented = true;
                    $habit->streaks += 1;
                    break;
                }
            }
        }

        if (!$incremented) $habit->streaks = 1;
        $habit->save();
    }
}
