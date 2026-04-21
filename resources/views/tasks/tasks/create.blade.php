@extends('components.layout')

@section('title')
    CREATE TASK
@endsection

@section('nav')
        <a href='{{ route('dashboard') }}'>board</a>
        <a href='{{ route('habits.index') }}'>habits</a>
        <a href='{{ route('tasks.index') }}'>tasks</a>
        <a href='{{ route('logs.index') }}'>historie</a>
        <a href='{{ route('categories.index') }}'>categories</a>
@endsection


@section('content')

<form action="{{ route('tasks.store') }}" method="POST">

    @csrf


    <input type="text" name = 'title' placeholder="title" required>
    @error('title')
        <div style="color:red;">{{ $message }}</div>
    @enderror

    <br>
    <textarea name="description" id="" cols="30" rows="10" placeholder="tesk or habit description"></textarea>
    @error('description')
        <div style="color:red;">{{ $message }}</div>
    @enderror
    <br>

    <select name = 'difficulty' required>
        <option value="xxs">xxs</option>
        <option value="xs">xs</option>
        <option value="s">s</option>
        <option value="m" selected>m</option>
        <option value="l">l</option>
        <option value="xl">xl</option>
        <option value="xxl">xxl</option>
    </select>
    @error('difficulty')
        <div style="color:red;">{{ $message }}</div>
    @enderror
    <br>

    <select name = 'priority' required>
        <option value="xxs">xxs</option>
        <option value="xs">xs</option>
        <option value="s">s</option>
        <option value="m" selected>m</option>
        <option value="l">l</option>
        <option value="xl">xl</option>
        <option value="xxl">xxl</option>
    </select>
    @error('priority')
        <div style="color:red;">{{ $message }}</div>
    @enderror
    <br>


    <input type="date" name = 'deadline' required>
    @error('deadline')
        <div style="color:red;">{{ $message }}</div>
    @enderror
    <br>

    @if (empty($categories)) 
        <p> no category created to select from </p>

    @else
        <select name = 'category_id'>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}">
                    {{ $category->title }}</option>
            @endforeach
        </select>
    @endif

    @error('category_id')
        <div style="color:red;">{{ $message }}</div>
    @enderror
    <br>

    <button>create</button>

</form>
@endsection