@extends('components.layout')

@section('title')
    EDIT HABIT
@endsection


@section('nav')
        <a href='{{ route('dashboard') }}'>board</a>
        <a href='{{ route('habits.index') }}'>habits</a>
        <a href='{{ route('tasks.index') }}'>tasks</a>
        <a href='{{ route('logs.index') }}'>historie</a>
        <a href='{{ route('categories.index') }}'>categories</a>
@endsection


@section('content')


@php
    $freq = $habit->frequency ?? [];
@endphp


<form action="{{ route('habits.update' , $habit->id) }}" method="POST">
    @csrf
    @method('PUT')

    <input type="text" name = 'title' placeholder="title" value="{{ $habit->title }}">
    @error('frequency')
        <div style="color:red;">{{ $message }}</div>
    @enderror

    <br>
    <textarea name="description" id="" cols="30" rows="10" placeholder="tesk or habit description"> {{ $habit->description }}</textarea>
    @error('description')
        <div style="color:red;">{{ $message }}</div>
    @enderror
    <br>

    <select name = 'difficulty'>
        <option @if ($habit->difficulty === 'xxs') selected @endif value="xxs">xxs</option>
        <option @if ($habit->difficulty === 'xs') selected @endif value="xs">xs</option>
        <option @if ($habit->difficulty === 's') selected @endif value="s">s</option>
        <option @if ($habit->difficulty === 'm') selected @endif value="m">m</option>
        <option @if ($habit->difficulty === 'l') selected @endif value="l">l</option>
        <option @if ($habit->difficulty === 'xl') selected @endif value="xl">xl</option>
        <option @if ($habit->difficulty === 'xxl') selected @endif value="xxl">xxl</option>
    </select>
    @error('difficulty')
        <div style="color:red;">{{ $message }}</div>
    @enderror
    <br>

    <select name = 'priority' required>
        <option @if ($habit->priority == 'xxs') selected @endif value="xxs">xxs</option>
        <option @if ($habit->priority == 'xs') selected @endif value="xs">xs</option>
        <option @if ($habit->priority == 's') selected @endif value="s">s</option>
        <option @if ($habit->priority == 'm') selected @endif value="m">m</option>
        <option @if ($habit->priority == 'l') selected @endif value="l">l</option>
        <option @if ($habit->priority == 'xl') selected @endif value="xl">xl</option>
        <option @if ($habit->priority == 'xxl') selected @endif value="xxl">xxl</option>
    </select>

    @error('priority')
        <div style="color:red;">{{ $message }}</div>
    @enderror
    <br>



    <section>
        
        <label><input type="checkbox" name="frequency[]" @if (in_array('Monday', $freq)) checked @endif
                value="Monday"> Monday</label>
        <label><input type="checkbox" name="frequency[]" @if (in_array('Tuesday', $freq)) checked @endif
                value="Tuesday"> Tuesday</label>
        <label><input type="checkbox" name="frequency[]" @if (in_array('Wednesday', $freq)) checked @endif
                value="Wednesday"> Wednesday</label>
        <label><input type="checkbox" name="frequency[]" @if (in_array('Thursday', $freq)) checked @endif
                value="Thursday"> Thursday</label>
        <label><input type="checkbox" name="frequency[]" @if (in_array('Friday', $freq)) checked @endif
                value="Friday"> Friday</label>
        <label><input type="checkbox" name="frequency[]" @if (in_array('Saturday', $freq)) checked @endif
                value="Saturday"> Saturday</label>
        <label><input type="checkbox" name="frequency[]" @if (in_array('Sunday', $freq)) checked @endif
                value="Sunday"> Sunday</label>
    </section>

    @error('frequency')
        <div style="color:red;">{{ $message }}</div>
    @enderror
    <br>


    <select name = 'category_id'>
        <option value=""> no category </option>
        @forelse ($categories as $category)
            <option value="{{ $category->id }}" @if ($category->id == $habit?->category_id) selected @endif>
                {{ $category->title }}</option>
        @empty
            <option value=""> no category created </option>
        @endforelse
    </select>
    @error('category_id')
        <div style="color:red;">{{ $message }}</div>
    @enderror
    <br>

    <button>create</button>

</form>
@endsection