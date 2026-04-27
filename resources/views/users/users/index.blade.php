@extends('components.layout')

@section('title')
    CONTROLL PANEL
@endsection

@section('nav')
    <a href="{{ route('blackList') }}">black list</a>
    <a href="{{ route('users.index') }}">users</a>
    <a href="{{ route('posts.hidden') }}">posts</a>
    <a href="{{ route('reports.index') }}">reports</a>
    @can('ban', App\Models\User::class)
        <a href="{{ route('categories.global') }}">categories</a>
    @endcan
@endsection

@section('content')
    <section class="mx-auto w-full max-w-6xl">

        <div class="mb-6 px-6 py-5">
            <div class="flex items-center justify-center">
                <form action="" method="GET" class="flex gap-2 sm:w-[70%] w-full ">
                    <input type="text" name="like" placeholder="search for a user by he's name or email"
                        class=" w-[100%] p-1 px-2 bg-[#151b23] border border-solid border-white/20 rounded-lg focus:bg-transparent focus:outline-blue-500 focus:outline-2 ">
                    <button
                        class="p-1 px-3 bg-[#151b23] border border-solid border-white/30 rounded-lg transition hover:border-white/60">
                        search
                    </button>
                </form>
            </div>
        </div>

        <div class="mb-6 rounded-2xl border border-white/10 bg-[#151b23] px-6 py-5 shadow-lg">
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-xl font-bold tracking-wide text-white">
                        {{ request()->routeIs(['blackList']) ? 'Black list' : 'active Users' }}</h2>
                    <p class="mt-2 text-sm text-[#9198a1]">
                        {{ request()->routeIs(['blackList'])
                            ? 'Review banned users and restore access when needed'
                            : 'Review active users and restore access when needed' }}

                    </p>
                </div>

                <div class="rounded-xl border border-white/10 bg-[#0d1117] px-4 py-3 hidden md:block">
                    <p class="text-xs uppercase tracking-[0.2em] text-[#9198a1]">Total
                        {{ request()->routeIs(['blackList']) ? 'banned' : 'Active' }} users</p>
                    <p class="mt-2 text-lg font-semibold text-white">{{ count($users) }}</p>
                </div>
            </div>
        </div>


        <div class="flex flex-col md:gap-5 gap-2 ">
            @forelse ($users as $user)
                <article class="rounded-2xl border border-white/10 bg-[#151b23] p-2 md:p-5 shadow-lg">
                    <div class="flex gap-5 justify-between items-center lg:items-center lg:justify-between">
                        
                        <div class="flex items-start gap-4">
                            <img src="{{ asset($user->image?->path ? 'storage/' . $user->image->path : 'images/blank-profile.webp') }}"
                                alt="{{ $user->name }}"
                                class="h-10 w-10 md:h-16 md:w-16  rounded-full border border-white/20 bg-[#0d1117] object-cover">

                            <div class="flex-1">

                                <a href="{{ route('users.show', $user->id) }}"
                                    class="md:text-lg text-md font-semibold text-white transition hover:text-blue-300">
                                    {{ $user->name }}
                                </a>

                                <p class="mt-1 text-sm text-[#9198a1]">{{ $user->email }}</p>

                                <div class="mt-4 flex flex-wrap gap-3">
                                    <span
                                        class="rounded-full border border-white/10 bg-[#0d1117] px-3 py-1 text-sm text-white hidden md:inline">
                                        score : {{ $user->score }}
                                    </span>
                                    @if ($user->is_banned || $user->is_banned_by_moderator)
                                        <span
                                            class="rounded-full border border-red-400/20 bg-red-500/10 px-3 py-1 text-sm text-red-200">
                                            {{ $user->is_banned ? 'banned by admin' : 'banned by moderator' }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- buttons --}}
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('users.show', $user->id) }}"
                                class="rounded-lg border border-white/10 bg-[#0d1117] px-5 py-2 text-sm font-medium text-[#9198a1] transition hover:border-white/20 hover:text-white hidden md:inline">
                                view profile
                            </a>

                            @if ($user->role != 'Admin' && auth()->user()->id != $user->id)
                                @if ($user->role == 'Moderator' && auth()->user()->role == 'Admin')
                                    <form
                                        action="{{ auth()->user()->role === 'Admin' ? route('admin.users.ban', $user) : route('moderator.users.ban', $user) }}"
                                        method="POST">
                                        @csrf
                                        <button
                                            class="rounded-full cursor-pointer border border-red-400/30 bg-red-500/10  px-5 py-2 text-sm font-medium text-red-200 transition hover:bg-red-500/20">
                                            @if ($user->is_banned || $user->is_banned_by_moderator)
                                                unban
                                            @else
                                                ban
                                            @endif
                                        </button>

                                    </form>
                                @elseif ($user->role != 'Moderator')
                                    <form
                                        action="{{ auth()->user()->role === 'Admin' ? route('admin.users.ban', $user) : route('moderator.users.ban', $user) }}"
                                        method="POST">
                                        @csrf
                                        <button
                                            class="rounded-full cursor-pointer  px-5 py-2 border border-red-400/30 bg-red-500/10 text-sm font-medium text-red-200 transition hover:bg-red-500/20">
                                            @if ($user->is_banned || $user->is_banned_by_moderator)
                                                unban
                                            @else
                                                ban
                                            @endif
                                        </button>

                                    </form>
                                @endif
                            @endif
                        </div>
                    </div>
                </article>
            @empty
                <div class="rounded-2xl border border-dashed border-white/15 bg-[#151b23] p-8 text-center shadow-lg">
                    <p class="text-base text-[#9198a1]">no banned users</p>
                </div>
            @endforelse
        </div>
    </section>
@endsection
