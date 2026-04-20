@extends('components.layout')


@section('title')
    REPORTS
@endsection


@section('content')

@forelse ($reports as $report) 


    @php
        $post = $report?->post ;
        $comments = $post?->comments ;
        $user = $post?->user ;
    @endphp

    <p> ------------- post ------------</p>
    <p>{{ $post->id }}</p>
    <p>{{ $post->content }}</p>
    <p>- comments</p>
    @forelse ($comments as $c )
        <pre>   - {{ $c->content }}</pre>
    @empty
        <pre>   - no comments</pre>
    @endforelse

    <p> ------------- reports ------------</p>
    <p>{{ $user->email}} make a report : </p>
    <p>type : {{ $report->type }}</p>
    <p>content : {{ $report->description }}</p>

    <a href="{{ route('reports.show' , $report->id) }}">show</a>

    <form action="{{ route("reports.destroy" , $report->id) }}" method='POST'>
        @csrf
        @method('DELETE')
        <button>delete</button>
    </form>
    @can('confirm' , \App\Models\Report::class)
    <form action="{{ route("reports.confirm" , $report->id) }}" method='POST'>
        @csrf
        <button>confirm</button>
    </form>
    @endcan

    <p>-----------------------------------------------</p>
    <p>-----------------------------------------------</p>
    <p>-----------------------------------------------</p>

@empty
    <p>no reporsts</p>
@endforelse