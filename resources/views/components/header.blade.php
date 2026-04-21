<header
    class="sticky top-0 px-8 py-2 text-blue-100 shadow-lg border-b border-white/30 border-solid bg-[#151b23]">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="mt-2 text-xl font-bold tracking-wide text-white">
                @yield('title', 'page')
            </h1>
        </div>

        <div class="text-sm text-blue-100/90 flex gap-10 items-center ">
            <nav class="flex gap-5 text-white">
                @yield('nav')
            </nav>
            <a href="{{ route('users.show' , auth()->id()) }}">
                <img src="{{ asset('storage/' . auth()->user()->image?->path) }}" alt="profile image"
                    class='w-[40px] aspect-square rounded-full '>
            </a>
        </div>

    </div>
</header>
