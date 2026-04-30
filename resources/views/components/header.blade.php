<header
    class="sticky top-0 border-b border-white/30 border-solid bg-[#151b23] px-4 py-3 text-blue-100 shadow-lg z-100">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        
        <div class="flex items-center gap-3 ">
            <button type="button" data-sidebar-toggle aria-expanded="false"
                class="flex h-10 w-10 items-center justify-center rounded-lg border border-white/20 bg-[#0d1117] text-white lg:hidden">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                    aria-hidden="true">
                    <path d="M4 7H20M4 12H20M4 17H20" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" />
                </svg>
            </button>
            <h1 class="text-xl font-bold tracking-wide text-white">
                @yield('title', 'page')
            </h1>
        </div>

        <div class="lg:flex items-center flex-col gap-3 text-sm text-blue-100/90 lg:flex-row lg:items-center lg:gap-10">
            <nav class="flex m-auto w-fit items-center gap-4 overflow-x-auto whitespace-nowrap text-white lg:gap-5 [&::-webkit-scrollbar]:hidden">
                @yield('nav')
            </nav>
            <a href="{{ route('users.show' , auth()->id()) }}">
                <img src="{{ asset('storage/' . auth()->user()->image?->path) }}" alt="profile image"
                    class='w-[40px] aspect-square rounded-full hidden lg:block object-cover '>
            </a>
        </div>

    </div>
</header>
