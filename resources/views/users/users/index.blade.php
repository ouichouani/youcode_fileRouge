@extends('components.layout')

@section('title')
    BLACK LIST
@endsection

@section('nav')
    <nav>
        <a href='{{ route('dashboard') }}'>board</a>
        <a href='{{ route('habits.index') }}'>habits</a>
        <a href='{{ route('tasks.index') }}'>tasks</a>
        <a href='{{ route('logs.index') }}'>historie</a>
        <a href='{{ route('categories.index') }}'>categories</a>
    </nav>
@endsection

@section('content')
    <nav>
        @can('ban', App\Models\User::class)
            <a href="{{ route('users.index') }}">active users</a>
            <a href="{{ route('categories.global') }}">global categories</a>
        @endcan
        <a href="{{ route('posts.hidden') }}">hidden posts</a>
        <a href="{{ route('reports.index') }}">reports</a>
    </nav>


    @forelse ($users as $user)
        <a href="{{ route('users.show', $user->id) }}">
            <p>{{ $user->name }}</p>
            <p>{{ $user->email }}</p>
            <p>{{ $user->score }}</p>
        </a>

        <form
            action="{{ Auth::user()->role === 'Admin' ? route('admin.users.ban', $user) : route('moderator.users.ban', $user) }}"
            method="POST">
            @csrf
            @if ($user->is_banned || $user->is_banned_by_moderator)
                <button>unban</button>
            @else
                <button>ban</button>
            @endif
            <p>{{ $user->is_banned || $user->is_banned_by_moderator ? 'true' : 'false' }}</p>
        </form>


        <br>
    @empty
        <p>no users</p>
    @endforelse
@endsection
