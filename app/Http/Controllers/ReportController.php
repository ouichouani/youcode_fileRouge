<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReportRequest;
use App\Http\Requests\UpdateReportRequest;
use App\Models\Post;
use App\Models\Report;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Request;

class ReportController extends Controller
{

    public function index()
    {
        // $this->authorize('viewAny', Report::class);

        // $reports = Report::query()->with([
        //     'user:id,name,email',
        //     'post.user:id,name,email',
        //     'post.user.image',

        //     'post.images',
        //     'user.image',

        //     'post.comments.user:id,name,email',
        //     'post.comments.user.image',

        // ])->latest();

        $this->authorize('viewAny', Report::class);

        $posts = Post::with([
            'user.image',
            'reports.user.image' ,
            'images',
            'comments.user.image',
        ])->where('is_hidden' , false)
        ->latest();
        
        if(Auth::user()->role == 'Admin'){
            $posts = $posts->whereHas('reports' , function($q){ $q->where('is_confirmed' , true); })->with('reports' , function($q){ $q->where('is_confirmed' , true); }) ;
        }else{
            $posts = $posts->whereHas('reports' , function($q){ $q->where('is_confirmed' , false); })->with('reports' , function($q){ $q->where('is_confirmed' , false); }) ;
        }

        $posts = $posts->get() ;
        return view('reports.index', compact('posts'));
    }


    public function create(Request $request)
    {
        $this->authorize('store' , Report::class);
        $post_id = $request->query('post') ;
        return view('reports.create' , compact('post_id'));
    }


    public function store(StoreReportRequest $request)
    {
        $this->authorize('store' ,  Report::class) ;
        $report = $request->validated();
        $report['user_id'] = Auth::id() ;  
        $report = Report::create($report);
        return redirect()->route('posts.index')->with('success', 'Report created successfully.');
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
