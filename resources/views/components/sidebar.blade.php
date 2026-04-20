<aside class="w-[20vw] h-screen p-[10rm] border-solid border-green-500 border">

    {{-- leading = line height --}}
    <ul class="flex flex-col text-green-500 font-semibold text-[1.5em] leading-[2em] pt-[2em] pl-[1em]">
        <a href='{{ route('dashboard') }}'>dashboard</a>
        <a href='{{ route('posts.index') }}'>explore</a>
        <a href='{{ route('users.profile') }}'>profile</a>
        <a href='{{ route('notifications.index') }}'>notification</a>

        @can('manage_app', App\Models\User::class)
            <a href='{{ route('blackList') }}'>controll panel</a>
        @endcan
    </ul>


</aside>
