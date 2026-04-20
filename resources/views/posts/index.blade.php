@extends('components.layout')

@section('title')
    POSTS
@endsection


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