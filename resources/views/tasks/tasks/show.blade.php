@extends('components.layout')


@section('title')
    SHOW TASK
@endsection


@section('nav')
        <a href='{{ route('dashboard') }}'>board</a>
        <a href='{{ route('habits.index') }}'>habits</a>
        <a href='{{ route('tasks.index') }}'>tasks</a>
        <a href='{{ route('logs.index') }}'>historie</a>
        <a href='{{ route('categories.index') }}'>categories</a>
@endsection


@section('content')


<div style="max-width: 700px; margin: 30px auto; font-family: Arial, sans-serif;">
    <h1>Task Details</h1>

    <div style="padding: 20px; border: 1px solid #ddd; border-radius: 10px;">
        <p><strong>ID:</strong> {{ $task->id }}</p>
        <p><strong>Title:</strong> {{ $task->title }}</p>
        <p><strong>Description:</strong> {{ $task->description ?: 'No description' }}</p>
        <p><strong>Difficulty:</strong> {{ $task->difficulty }}</p>
        <p><strong>Priority:</strong> {{ $task->priority }}</p>

        <p>
            <strong>Deadline:</strong>
            {{ $task->deadline ? $task->deadline->format('Y-m-d H:i') : 'No deadline' }}
        </p>

        <p><strong>Status:</strong> {{ $task->done ? 'Done' : 'Not done' }}</p>
        <p><strong>Streaks:</strong> {{ $task->streaks ?? 0 }}</p>

        <p>
            <strong>Frequency:</strong>
            @if (!empty($task->frequency) && is_array($task->frequency))
                {{ implode(', ', $task->frequency) }}
            @else
                No frequency set
            @endif
        </p>

        <p><strong>Category:</strong> {{ $task->category?->title ?? 'No category' }}</p>
        <p><strong>User ID:</strong> {{ $task->user_id }}</p>
        <p><strong>Created At:</strong> {{ $task->created_at?->format('Y-m-d H:i') }}</p>
        <p><strong>Updated At:</strong> {{ $task->updated_at?->format('Y-m-d H:i') }}</p>

        <a href='{{ route('tasks.edit' , $task->id) }}'>update</a>
        <form action='{{ route('tasks.destroy' , $task->id) }}' method='post'>
            @method('DELETE')
            @csrf
            <button>delete</button>
        </form>
    </div>
</div>
@endsection