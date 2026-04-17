<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReportRequest;
use App\Http\Requests\UpdateReportRequest;
use App\Models\Report;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{

    public function index()
    {
        $this->authorize('viewAny', Report::class);

        $reports = Report::query()->with([
            'user:id,name,email',
            'post.user:id,name,email',
            'post.user.image',

            'post.images',
            'user.image',

            'post.comments.user:id,name,email',
            'post.comments.user.image',

        ])->latest();
        
        if(Auth::user()->role == 'Admin'){
            $reports = $reports->where('is_confirmed' , true) ;
        }

        $reports = $reports->get() ;
        return view('reports.index', compact('reports'));
    }


    public function create()
    {
        return view('reports.create');
    }


    public function store(StoreReportRequest $request)
    {
        $report = $request->validated();
        $report = Report::create($report);
        return redirect()->back()->with('success', 'Report created successfully.');
    }


    public function show(Report $report)
    {
        $this->authorize('view', $report);

        $report->load([
            'user:id,name,email',
            'post.user:id,name,email',
            'post.user.image',

            'post.images',
            'user.image',

            'post.comments.user:id,name,email',
            'post.comments.user.image',

        ])->latest()->get();

        // dd($report);
        $post = $report?->post ;
        $comments = $post?->comments ;
        $user = $post?->user ;

        return view('reports.show', compact('report' , 'post', 'comments' , 'user'));
    }


    public function edit(Report $report)
    {
        $this->authorize('update', $report);
        return view('reports.edit', compact('report'));
    }

    public function destroy(Report $report)
    {
        $this->authorize('delete', $report);
        $report->delete();
        return redirect()->route('reports.index')->with('success', 'Report deleted successfully.');
    }
}
