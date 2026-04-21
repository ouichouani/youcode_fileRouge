@extends('components.layout')

@section('title')
    HISTORY
@endsection

@section('nav')
        <a href='{{ route('dashboard') }}'>board</a>
        <a href='{{ route('habits.index') }}'>habits</a>
        <a href='{{ route('tasks.index') }}'>tasks</a>
        <a href='{{ route('logs.index') }}'>historie</a>
        <a href='{{ route('categories.index') }}'>categories</a>
@endsection


@section('content')

@forelse ($logs as $l )
    <p> - {{$l->created_at}}</p>
@empty
    <p>no logs yet</p>
@endforelse

@endsection