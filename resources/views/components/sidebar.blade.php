<aside
    class="min-h-screen w-[15vw] min-w-[200px] bg-[#151b23] px-2 py-10 text-white shadow-2xl sticky border-r-[2px] border-white/30 border-solid">
    
    <ul class="flex flex-col gap-4 ">
        <a class="rounded-lg px-2 py-2 transition hover:bg-[#212830] flex gap-2 {{ request()->routeIs(['dashboard', 'tasks.index', 'habits.index', 'categories.index' , 'logs.index']) ? 'bg-[#212830] border-l-4 rounded-l-none border-blue-500' : '' }}"
            href='{{ route('dashboard') }}'><img class='w-[20px] stroke-red-100 hover:stock-blue-500' src="{{ asset('svg/dashboard.svg') }}" alt=""> dashboard</a>

        <a class="rounded-lg px-2 py-2 transition hover:bg-[#212830] flex gap-2 {{ request()->routeIs(['posts.index']) ? 'bg-[#212830] border-l-4 rounded-l-none border-blue-500' : '' }}"
            href='{{ route('posts.index') }}'><img class='w-[20px] fill-white/50 hover:fill-white' src="{{ asset('svg/explore.svg') }}" alt=""> explore</a>

        <a class="rounded-lg px-2 py-2 transition hover:bg-[#212830] flex gap-2 {{ request()->routeIs(['users.edit' , 'users.profile' , 'posts.edit' , 'users.show']) ? 'bg-[#212830] border-l-4 rounded-l-none border-blue-500' : '' }}"
            href='{{ route('users.profile') }}'><img class='w-[20px] fill-white/50 hover:fill-white' src="{{ asset('svg/profile.svg') }}" alt=""> profile</a>
            
        {{-- <a class="rounded-lg px-2 py-2 transition hover:bg-[#212830] flex gap-2 {{ request()->routeIs(['notifications.index']) ? 'bg-[#212830] border-l-4 rounded-l-none border-blue-500' : '' }}"
            href='{{ route('notifications.index') }}'><img class='w-[20px] fill-white/50 hover:fill-white' src="{{ asset('svg/notification.svg') }}" alt=""> notification</a> --}}

        <a class="rounded-lg px-2 py-2 transition hover:bg-[#212830] flex gap-2 {{ request()->routeIs(['requests.index']) ? 'bg-[#212830] border-l-4 rounded-l-none border-blue-500' : '' }}"
            href='{{ route('requests.index') }}'><img class='w-[25px] fill-white/50 hover:fill-white' src="{{ asset('svg/requests.svg') }}" alt=""> requests</a>

        @can('manage_app', App\Models\User::class)
        <a class="rounded-lg px-2 py-2 transition hover:bg-[#212830] flex gap-2 {{ request()->routeIs(['blackList' , 'users.index' , 'reports.index' , 'categories.global' , 'posts.hidden']) ? 'bg-[#212830] border-l-4 rounded-l-none border-blue-500' : '' }}"
                href='{{ route('blackList') }}'><img class='w-[20px] fill-white/50 hover:fill-white' src="{{ asset('svg/settings.svg') }}" alt=""> controll panel</a>
        @endcan

        {{-- <a class="rounded-lg px-2 py-2 transition hover:bg-[#212830] flex gap-2"
                href='{{ route('blackList') }}'><img class='w-[20px] fill-white/50 hover:fill-white' src="{{ asset('svg/settings.svg') }}" alt=""> about</a> --}}
    </ul>
</aside>

