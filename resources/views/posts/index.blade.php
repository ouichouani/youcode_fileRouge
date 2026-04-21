@extends('components.layout')

@section('title')
    @if (auth()->user()->role == 'Admin' || auth()->user()->role == 'Moderator')
        CONTROLL PANEL
    @else
        POSTS
    @endif
@endsection

@section('nav')
<a href='{{ route('posts.create') }}'>create post</a>
<a href='{{ route('posts.index') }}'>all posts</a>

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

    @forelse ($posts as $post)
        <div style="border: 1px solid red ; padding: 10px ; ">
            @if (count($post?->reports) == 0)
                @can('store', App\Models\Report::class)
                    <a href="{{ route('reports.create', ['post' => $post->id]) }}">report</a>
                @endif
            @endcan
            ----------
            <a href="{{ route('users.show', $post->user->id) }}">
                <h1>{{ $post->user->name }}</h1>
            </a>
            <p>{{ $post->content }}</p>
            <p>{{ $post->type }}</p>
            <p>{{ count($post->likes) }}</p>
            <p>------comments--------</p>
            @forelse ($post->comments as $comment)
                <pre>   - {{ $comment->content }}</pre>
                <pre>           - {{ $comment->created_at->diffForHumans() }}</pre>
            @empty
                <p>no comments ...</p>
            @endforelse
        </div>
        <br>
    @empty
        <p>there is no posts yet</p>
    @endforelse

@endsection
