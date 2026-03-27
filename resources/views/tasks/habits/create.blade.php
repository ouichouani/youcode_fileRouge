<form action="{{ route('habits.store') }}" method="POST">
    
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


    <section>
        <label><input type="checkbox" name="frequency[]" checked value="Monday"> Monday</label>
        <label><input type="checkbox" name="frequency[]" checked value="Tuesday"> Tuesday</label>
        <label><input type="checkbox" name="frequency[]" checked value="Wednesday"> Wednesday</label>
        <label><input type="checkbox" name="frequency[]" checked value="Thursday"> Thursday</label>
        <label><input type="checkbox" name="frequency[]" checked value="Friday"> Friday</label>
        <label><input type="checkbox" name="frequency[]" checked value="Saturday"> Saturday</label>
        <label><input type="checkbox" name="frequency[]" checked value="Sunday"> Sunday</label>
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

    <button>create</button>

</form>
