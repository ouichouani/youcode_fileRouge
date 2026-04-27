<aside
    data-sidebar
    class="fixed inset-y-0 left-0 z-200 w-[80vw] min-w-0 max-w-[320px] -translate-x-full bg-[#151b23] px-2 py-10 text-white shadow-2xl border-r-[2px] border-white/30 border-solid transition-transform duration-200 lg:sticky lg:z-auto lg:w-[15vw] lg:min-w-[200px] lg:max-w-none lg:translate-x-0">

    <div class="mb-6 flex justify-end lg:hidden">
        <button type="button" data-sidebar-close
            class="flex h-10 w-10 items-center justify-center rounded-lg border border-white/20 bg-[#0d1117] text-white"
            aria-label="Close menu">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                aria-hidden="true">
                <path d="M6 6L18 18M18 6L6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
            </svg>
        </button>
    </div>

    <ul class="flex flex-col gap-4 sticky top-10">
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

<button data-sidebar-backdrop
    class="fixed inset-0 z-30 hidden bg-black/50 lg:hidden"
    aria-label="Close menu"></button>

@push('script')
    <script>
        (() => {
            const toggle = document.querySelector('[data-sidebar-toggle]');
            const sidebar = document.querySelector('[data-sidebar]');
            const backdrop = document.querySelector('[data-sidebar-backdrop]');
            const closeButton = document.querySelector('[data-sidebar-close]');

            if (!toggle || !sidebar || !backdrop) return;

            const closeSidebar = () => {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
                document.body.style.overflow = '';
                toggle.setAttribute('aria-expanded', 'false');
            };

            const openSidebar = () => {
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
                toggle.setAttribute('aria-expanded', 'true');
            };

            toggle.addEventListener('click', () => {
                if (sidebar.classList.contains('-translate-x-full')) {
                    openSidebar();
                } else {
                    closeSidebar();
                }
            });

            backdrop.addEventListener('click', closeSidebar);
            closeButton?.addEventListener('click', closeSidebar);

            window.addEventListener('resize', () => {
                if (window.innerWidth >= 1024) {
                    closeSidebar();
                }
            });
        })();
    </script>
@endpush
