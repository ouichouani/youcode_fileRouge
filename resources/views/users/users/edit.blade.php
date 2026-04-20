<div style="max-width: 500px; margin: 40px auto; font-family: Arial, sans-serif;">
    <h1>update Account</h1>
    <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data"
        style="display: grid; gap: 12px;">
        @csrf
        @method('PUT')

        <div>
            <label for="name">Name</label>
            <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required
                style="width: 100%; padding: 10px;">
            @error('name')
                <div style="color: red;">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="password">Password</label>
            <input id="password" type="password" name="password" style="width: 100%; padding: 10px;">
            @error('password')
                <div style="color: red;">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="password_confirmation">Confirm Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation"
                style="width: 100%; padding: 10px;">
        </div>

        <div>
            <label for="bio">Bio</label>
            <textarea id="bio" name="bio" rows="4" style="width: 100%; padding: 10px;">{{ old('bio', $user->bio) }}</textarea>
            @error('bio')
                <div style="color: red;">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="bio">image</label>
            <input type="file" name="images[]" multiple accept="image/*">
            @error('image')
                <div style="color: red;">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" style="padding: 10px 14px;">update</button>
    </form>


</div>
