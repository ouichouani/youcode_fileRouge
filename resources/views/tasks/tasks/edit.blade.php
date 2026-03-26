
@php
    $freq = $task->frequency ?? [] ;
@endphp


<form action="/tasks/1" method="POST">
    @csrf
    @method('PUT')

    <input type="text" name = 'title' placeholder="title" value="{{ $task->title }}">
    @error('frequency')
        <div style="color:red;">{{ $message }}</div>
    @enderror

    <br>
    <textarea name="description" id="" cols="30" rows="10" placeholder="tesk or habit description"> {{  $task->description }}</textarea>
    @error('description')
        <div style="color:red;">{{ $message }}</div>
    @enderror
    <br>

    <select name = 'difficulty'>
        <option @if($task->difficulty === 'xxs') selected @endif value="xxs" >xxs</option>
        <option @if($task->difficulty === 'xs')  selected @endif value="xs">xs</option>
        <option @if($task->difficulty === 's')   selected @endif value="s">s</option>
        <option @if($task->difficulty === 'm')   selected @endif value="m" >m</option>
        <option @if($task->difficulty === 'l')   selected @endif value="l">l</option>
        <option @if($task->difficulty === 'xl')  selected @endif value="xl">xl</option>
        <option @if($task->difficulty === 'xxl') selected @endif value="xxl">xxl</option>
    </select>
    @error('difficulty')
        <div style="color:red;">{{ $message }}</div>
    @enderror
    <br>

    <select name = 'priority' required>
        <option @if($task->priority == 'xxs') selected @endif value="xxs" >xxs</option>
        <option @if($task->priority == 'xs')  selected @endif value="xs">xs</option>
        <option @if($task->priority == 's')   selected @endif value="s">s</option>
        <option @if($task->priority == 'm')   selected @endif value="m" >m</option>
        <option @if($task->priority == 'l')   selected @endif value="l">l</option>
        <option @if($task->priority == 'xl')  selected @endif value="xl">xl</option>
        <option @if($task->priority == 'xxl') selected @endif value="xxl">xxl</option>
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


    <section>
        
        <label><input type="checkbox" name="frequency[]" @if(in_array("OneTime" ,$freq)) checked @endif    value="OneTime"> OneTime</label>
        <label><input type="checkbox" name="frequency[]" @if(in_array("Monday" ,$freq)) checked @endif value="Monday"> Monday</label>
        <label><input type="checkbox" name="frequency[]" @if(in_array("Tuesday" ,$freq)) checked @endif    value="Tuesday"> Tuesday</label>
        <label><input type="checkbox" name="frequency[]" @if(in_array("Wednesday" ,$freq)) checked @endif  value="Wednesday"> Wednesday</label>
        <label><input type="checkbox" name="frequency[]" @if(in_array("Thursday" ,$freq)) checked @endif   value="Thursday"> Thursday</label>
        <label><input type="checkbox" name="frequency[]" @if(in_array("Friday" ,$freq)) checked @endif value="Friday"> Friday</label>
        <label><input type="checkbox" name="frequency[]" @if(in_array("Saturday" ,$freq)) checked @endif   value="Saturday"> Saturday</label>
        <label><input type="checkbox" name="frequency[]" @if(in_array("Sunday" ,$freq)) checked @endif value="Sunday"> Sunday</label>
    </section>

    @error('frequency')
        <div style="color:red;">{{ $message }}</div>
    @enderror
    <br>


    {{-- {{ dd($task?->category_id) }} --}}
    <select name = 'category_id' required>
        <option value=""> no category </option>
        @foreach ($categories as $category)
            <option value="{{ $category->id }}" @if($category->id == $task?->category_id) selected @endif>{{ $category->title }}</option>
        @endforeach
    </select>
    @error('category_id')
        <div style="color:red;">{{ $message }}</div>
    @enderror
    <br>

    <button>create</button>

</form>


