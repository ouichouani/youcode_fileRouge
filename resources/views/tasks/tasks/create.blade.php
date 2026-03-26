<form action="/tasks" method="POST">
    @csrf


    <input type="text" name = 'title' placeholder="title" required>
    @error('frequency')
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


    <section>
        <label><input type="checkbox" name="frequency[]" value="OneTime"> OneTime</label>
        <label><input type="checkbox" name="frequency[]" value="Monday"> Monday</label>
        <label><input type="checkbox" name="frequency[]" value="Tuesday"> Tuesday</label>
        <label><input type="checkbox" name="frequency[]" value="Wednesday"> Wednesday</label>
        <label><input type="checkbox" name="frequency[]" value="Thursday"> Thursday</label>
        <label><input type="checkbox" name="frequency[]" value="Friday"> Friday</label>
        <label><input type="checkbox" name="frequency[]" value="Saturday"> Saturday</label>
        <label><input type="checkbox" name="frequency[]" value="Sunday"> Sunday</label>
    </section>
    @error('frequency')
        <div style="color:red;">{{ $message }}</div>
    @enderror
    <br>


    <select name = 'category_id' required>
        @foreach ($categories as $category)
            <option value="{{ $category->id }}">{{ $category->title }}</option>
            {{-- <p> - </p> --}}
        @endforeach
    </select>
    @error('category_id')
        <div style="color:red;">{{ $message }}</div>
    @enderror
    <br>

    <button>create</button>

</form>
