<div style="max-width: 420px; margin: 40px auto; font-family: Arial, sans-serif;">
    <h1>Login</h1>

    @if (session('error'))
        <div style="color: red; margin-bottom: 12px;">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('login') }}" method="POST" style="display: grid; gap: 12px;">
        @csrf

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

        <button type="submit" style="padding: 10px 14px;">Login</button>
    </form>

    <p style="margin-top: 16px;">
        Don't have an account?
        <a href="{{ route('register') }}">Register</a>
    </p>
</div>
