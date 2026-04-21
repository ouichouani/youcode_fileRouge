@extends('components.layout')

@section('title')
    @if (auth()->user()->role == 'Admin' || auth()->user()->role == 'Moderator')
        CONTROLL PANEL
    @else
        CATEGORIES
    @endif
@endsection

@section('nav')
        <a href='{{ route('dashboard') }}'>board</a>
        <a href='{{ route('habits.index') }}'>habits</a>
        <a href='{{ route('tasks.index') }}'>tasks</a>
        <a href='{{ route('logs.index') }}'>historie</a>
        <a href='{{ route('categories.index') }}'>categories</a>
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
    @can('accessGlobalCategories', App\Models\Category::class)
        <a href="{{ route('categories.create') }}">create a global category</a>
    @endcan
    @forelse ($categories as $c)
        <p>{{ $c->title }}</p>
        @can('accessGlobalCategories', $c)
            <a href="{{ route('categories.edit', $c->id) }}">update for "{{ $c->title }}"</a>
            <form action="{{ route('categories.destroy', $c->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type='submit'>delete</button>
            </form>
        @endcan
    @empty
        <p>no cat created yet</p>
    @endforelse
@endsection
