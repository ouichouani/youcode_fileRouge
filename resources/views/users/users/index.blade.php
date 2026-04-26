@extends('components.layout')

@section('title')
    CONTROLL PANEL
@endsection

@section('nav')
    @can('ban', App\Models\User::class)
        <a href="{{ route('blackList') }}">black list</a>
        <a href="{{ route('users.index') }}">active users</a>
        <a href="{{ route('categories.global') }}">global categories</a>
    @endcan
    <a href="{{ route('posts.hidden') }}">hidden posts</a>
    <a href="{{ route('reports.index') }}">reports</a>
@endsection

@section('content')
    <section class="mx-auto w-full max-w-6xl px-4 py-6">
        <div class="mb-6 rounded-2xl border border-white/10 bg-[#151b23] px-6 py-5 shadow-lg">
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-xl font-bold tracking-wide text-white">{{  request()->routeIs(['blackList']) ? 'Black list' : 'active Users' }}</h2>
                    <p class="mt-2 text-sm text-[#9198a1]">
                        {{  request()->routeIs(['blackList']) ?
                        'Review banned users and restore access when needed' :
                        'Review active users and restore access when needed' }}
                        
                    </p>
                </div>
                <div class="rounded-xl border border-white/10 bg-[#0d1117] px-4 py-3">
                    <p class="text-xs uppercase tracking-[0.2em] text-[#9198a1]">Total {{ request()->routeIs(['blackList']) ? 'banned' : 'Active' }} users</p>
                    <p class="mt-2 text-lg font-semibold text-white">{{ count($users) }}</p>
                </div>
            </div>
        </div>

        <div class="flex flex-col gap-5">
            @forelse ($users as $user)
                <article class="rounded-2xl border border-white/10 bg-[#151b23] p-5 shadow-lg">
                    <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                        <div class="flex items-start gap-4">
                            <img src="{{ asset($user->image?->path ? 'storage/' . $user->image->path : 'images/blank-profile.webp') }}"
                                alt="{{ $user->name }}"
                                class="h-16 w-16 rounded-full border border-white/20 bg-[#0d1117] object-cover">

                            <div class="flex-1">
                                <a href="{{ route('users.show', $user->id) }}"
                                    class="text-lg font-semibold text-white transition hover:text-blue-300">
                                    {{ $user->name }}
                                </a>
                                <p class="mt-1 text-sm text-[#9198a1]">{{ $user->email }}</p>

                                <div class="mt-4 flex flex-wrap gap-3">
                                    <span class="rounded-full border border-white/10 bg-[#0d1117] px-3 py-1 text-sm text-white">
                                        score : {{ $user->score }}
                                    </span>
                                    @if($user->is_banned || $user->is_banned_by_moderator)
                                    <span class="rounded-full border border-red-400/20 bg-red-500/10 px-3 py-1 text-sm text-red-200">
                                        {{ $user->is_banned ? 'banned by admin' : 'banned by moderator' }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('users.show', $user->id) }}"
                                class="rounded-lg border border-white/10 bg-[#0d1117] px-5 py-2 text-sm font-medium text-[#9198a1] transition hover:border-white/20 hover:text-white">
                                view profile
                            </a>

                            @if($user->role != 'Admin')
                            <form
                                action="{{ Auth::user()->role === 'Admin' ? route('admin.users.ban', $user) : route('moderator.users.ban', $user) }}"
                                method="POST">
                                @csrf
                                <button
                                    class="rounded-lg border border-red-400/30 bg-red-500/10 px-5 py-2 text-sm font-medium text-red-200 transition hover:bg-red-500/20">
                                    @if ($user->is_banned || $user->is_banned_by_moderator)
                                        unban
                                    @else
                                        ban
                                    @endif
                                </button>
                            </form>
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
