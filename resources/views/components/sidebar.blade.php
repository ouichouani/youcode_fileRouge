<aside
    class="min-h-screen w-[20vw] min-w-[220px] bg-gradient-to-b from-black via-slate-950 to-blue-900 px-6 py-10 text-blue-100 shadow-2xl sticky ">
    <ul class="flex flex-col gap-4 text-lg font-semibold tracking-wide">
        <a class="rounded-xl border border-blue-500/20 px-4 py-3 transition hover:border-blue-300/50 hover:bg-blue-500/10 hover:text-white"
            href='{{ route('dashboard') }}'>dashboard</a>

        <a class="rounded-xl border border-blue-500/20 px-4 py-3 transition hover:border-blue-300/50 hover:bg-blue-500/10 hover:text-white"
            href='{{ route('posts.index') }}'>explore</a>

        <a class="rounded-xl border border-blue-500/20 px-4 py-3 transition hover:border-blue-300/50 hover:bg-blue-500/10 hover:text-white"
            href='{{ route('users.profile') }}'>profile</a>
            
        <a class="rounded-xl border border-blue-500/20 px-4 py-3 transition hover:border-blue-300/50 hover:bg-blue-500/10 hover:text-white"
            href='{{ route('notifications.index') }}'>notification</a>

        @can('manage_app', App\Models\User::class)
            <a class="rounded-xl border border-blue-500/20 px-4 py-3 transition hover:border-blue-300/50 hover:bg-blue-500/10 hover:text-white"
                href='{{ route('blackList') }}'>controll panel</a>
        @endcan
    </ul>
</aside>

{{-- 
bg-gradient-to-b = gradient to bottom '-b'
shadow-2xl = box shadow
tracking-wide = letter-spacing
rounded-xl = border radius
--}}
