@extends('components.layout')


@section('title')
    SHOW USER
@endsection


@section('content')
<img src="{{ asset('storage/' . $user?->image?->path) }}" alt=""
    style="width:200px;height:200px;background-color:red;border-radius:3000px">
<p>{{ $user->name }}</p>

<p> - followers : {{ count($sentRequests) }}</p>
<p> - followed by : {{ count($receivedRequests) }}</p>

<a href="{{ route('users.edit', $user->id) }}">update</a>

<form action="{{ route('logout') }}" method="POST">
    @csrf
    <button>logout</button>
</form>


@if (isset($pendingRequest) && $pendingRequest?->status == 'pending')

    @if ($pendingRequest?->sender_id == Auth::id())
        <p> - you sent a friend request to {{ $pendingRequest?->sender->name }} </p>
        <form action="{{ route('requests.destroy', $pendingRequest->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <button>cansel</button>
        </form>
    @endif

    @if ($pendingRequest?->receiver_id == Auth::id())
        <p> - {{ $pendingRequest?->sender->name }} send a friend request </p>
        <form action="{{ route('requests.reject', $pendingRequest->id) }}" method="POST">
            @csrf
            <button>reject</button>
        </form>
        <form action="{{ route('requests.accept', $pendingRequest->id) }}" method="POST">
            @csrf
            <button>accept</button>
        </form>
    @endif


@endif
<p>---------------posts------------</p>

@foreach ($posts as $p)
    @can('hide', $p)
        <form
            action="{{ auth()->user()->role === 'Admin' ? route('posts.hide', $p->id) : route('admin.post.hide', $p->id) }}"
            method='POST'>
            @csrf
            <button>hide</button>
        </form>
    @endcan

    
    @foreach($p?->images as $image)
    <img src="{{ asset('storage/' . $image?->path) }}" alt="" style="width: 200px;">
    @endforeach


    <p>{{ $p->content }}</p>
    <p>likes : {{ count($p->likes) }}</p>
    <p>is hidden : {{ $p->is_hidden ? 'true' : 'false' }}</p>

    @foreach ($p->comments as $c)
        <p> -- {{ $c->content }}</p>
    @endforeach

    @if ($user->id === $p->user_id)
        <a href="{{ route('posts.edit', $p->id) }}">update</a>
    @endif
@endforeach


@endsection