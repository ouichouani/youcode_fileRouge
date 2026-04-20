<div style="max-width: 500px; margin: 40px auto; font-family: Arial, sans-serif;">
    <h1>Create Account</h1>

    <form action="{{ route('register') }}" method="POST" enctype="multipart/form-data" style="display: grid; gap: 12px;">
        @csrf

        <div>
            <label for="name">Name</label>
            <input
                id="name"
                type="text"
                name="name"
                value="{{ old('name') }}"
                required
                style="width: 100%; padding: 10px;"
            >
            @error('name')
                <div style="color: red;">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="email">Email</label>
            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                style="width: 100%; padding: 10px;"
            >
            @error('email')
                <div style="color: red;">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="password">Password</label>
            <input
                id="password"
                type="password"
                name="password"
                required
                style="width: 100%; padding: 10px;"
            >
            @error('password')
                <div style="color: red;">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="password_confirmation">Confirm Password</label>
            <input
                id="password_confirmation"
                type="password"
                name="password_confirmation"
                required
                style="width: 100%; padding: 10px;"
            >
        </div>

        <div>
            <label for="bio">Bio</label>
            <textarea
                id="bio"
                name="bio"
                rows="4"
                style="width: 100%; padding: 10px;"
            >{{ old('bio') }}</textarea>
            @error('bio')
                <div style="color: red;">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="bio">image</label>
            <input type="file" name="image">
            @error('image')
                <div style="color: red;">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" style="padding: 10px 14px;">Register</button>
    </form>

    <p style="margin-top: 16px;">
        Already have an account?
        <a href="{{ route('login') }}">Login</a>
    </p>
</div>
