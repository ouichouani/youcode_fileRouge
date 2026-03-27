@php
    $freq = $task->frequency ?? [];
@endphp


<form action="{{ route('tasks.update' , $task->id) }}" method="POST">
    @csrf
    @method('PUT')

    <input type="text" name = 'title' placeholder="title" value="{{ $task->title }}">
    @error('frequency')
        <div style="color:red;">{{ $message }}</div>
    @enderror

    <br>
    <textarea name="description" id="" cols="30" rows="10" placeholder="tesk or habit description"> {{ $task->description }}</textarea>
    @error('description')
        <div style="color:red;">{{ $message }}</div>
    @enderror
    <br>

    <select name = 'difficulty'>
        <option @if ($task->difficulty === 'xxs') selected @endif value="xxs">xxs</option>
        <option @if ($task->difficulty === 'xs') selected @endif value="xs">xs</option>
        <option @if ($task->difficulty === 's') selected @endif value="s">s</option>
        <option @if ($task->difficulty === 'm') selected @endif value="m">m</option>
        <option @if ($task->difficulty === 'l') selected @endif value="l">l</option>
        <option @if ($task->difficulty === 'xl') selected @endif value="xl">xl</option>
        <option @if ($task->difficulty === 'xxl') selected @endif value="xxl">xxl</option>
    </select>
    @error('difficulty')
        <div style="color:red;">{{ $message }}</div>
    @enderror
    <br>

    <select name = 'priority' required>
        <option @if ($task->priority == 'xxs') selected @endif value="xxs">xxs</option>
        <option @if ($task->priority == 'xs') selected @endif value="xs">xs</option>
        <option @if ($task->priority == 's') selected @endif value="s">s</option>
        <option @if ($task->priority == 'm') selected @endif value="m">m</option>
        <option @if ($task->priority == 'l') selected @endif value="l">l</option>
        <option @if ($task->priority == 'xl') selected @endif value="xl">xl</option>
        <option @if ($task->priority == 'xxl') selected @endif value="xxl">xxl</option>
    </select>

    @error('priority')
        <div style="color:red;">{{ $message }}</div>
    @enderror
    <br>


    <input type="date" name = 'deadline' value="{{ $task->deadline->format('Y-m-d') }}" required>
    @error('deadline')
        <div style="color:red;">{{ $message }}</div>
    @enderror
    <br>


    <select name = 'category_id'>
        <option value=""> no category </option>
        @forelse ($categories as $category)
            <option value="{{ $category->id }}" @if ($category->id == $task?->category_id) selected @endif>
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
