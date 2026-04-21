@extends('components.layout')

@section('title')
    @if (auth()->user()->role == 'Admin' || auth()->user()->role == 'Moderator')
        CONTROLL PANEL
    @else
        POSTS
    @endif
@endsection


@if (auth()->user()->role == 'Admin' || auth()->user()->role == 'Moderator')
    @section('nav')
        @can('ban', App\Models\User::class)
            <a href="{{ route('blackList') }}">black list</a>
            <a href="{{ route('users.index') }}">active users</a>
            <a href="{{ route('categories.global') }}">global categories</a>
        @endcan
        <a href="{{ route('posts.hidden') }}">hidden posts</a>
        <a href="{{ route('reports.index') }}">reports</a>
    @endsection
@endif

@section('content')

    @forelse ($reports as $report)


        @php
            $post = $report?->post;
            $comments = $post?->comments;
            $user = $post?->user;
        @endphp

        <p> ------------- post ------------</p>
        <p>{{ $post->id }}</p>
        <p>{{ $post->content }}</p>
        <p>- comments</p>
        @forelse ($comments as $c)
            <pre>   - {{ $c->content }}</pre>
        @empty
            <pre>   - no comments</pre>
        @endforelse

        <p> ------------- reports ------------</p>
        <p>{{ $user->email }} make a report : </p>
        <p>type : {{ $report->type }}</p>
        <p>content : {{ $report->description }}</p>

        <a href="{{ route('reports.show', $report->id) }}">show</a>

        <form action="{{ route('reports.destroy', $report->id) }}" method='POST'>
            @csrf
            @method('DELETE')
            <button>delete</button>
        </form>
        @can('confirm', \App\Models\Report::class)
            <form action="{{ route('reports.confirm', $report->id) }}" method='POST'>
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
@endsection