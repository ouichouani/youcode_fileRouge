@extends('components.layout')


@section('title')
    SHOW USER
@endsection


@section('content')
<section class="mx-auto w-full max-w-6xl px-4 py-6">
    <div class="mb-6 rounded-2xl border border-white/10 bg-[#151b23] p-6 shadow-lg">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
            <div class="flex flex-1 items-start gap-5">
                <img src="{{ asset('storage/' . $user?->image?->path) }}" alt="{{ $user->name }}"
                    class="h-28 w-28 rounded-full border border-white/20 bg-[#0d1117] object-cover">
                <div>
                    <h2 class="text-3xl font-bold text-white">{{ $user->name }}</h2>
                    <div class="mt-4 grid gap-3 sm:grid-cols-2">
                        <div class="rounded-xl border border-white/10 bg-[#0d1117] px-4 py-3">
                            <p class="text-xs uppercase tracking-[0.2em] text-[#9198a1]">Followers</p>
                            <p class="mt-2 text-lg font-semibold text-white">{{ count($sentRequests) }}</p>
                        </div>
                        <div class="rounded-xl border border-white/10 bg-[#0d1117] px-4 py-3">
                            <p class="text-xs uppercase tracking-[0.2em] text-[#9198a1]">Followed by</p>
                            <p class="mt-2 text-lg font-semibold text-white">{{ count($receivedRequests) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('users.edit', $user->id) }}"
                    class="rounded-lg border border-white/20 bg-[#0d1117] px-5 py-2 text-sm font-medium text-white transition hover:border-white/50">
                    update
                </a>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button
                        class="rounded-lg border border-red-400/30 bg-red-500/10 px-5 py-2 text-sm font-medium text-red-200 transition hover:bg-red-500/20">
                        logout
                    </button>
                </form>
            </div>
        </div>
    </div>

    @if (isset($pendingRequest) && $pendingRequest?->status == 'pending')
        <section class="mb-6 rounded-2xl border border-white/10 bg-[#151b23] p-6 shadow-lg">
            <h3 class="mb-4 text-xl font-bold text-white">Friend request</h3>

            @if ($pendingRequest?->sender_id == Auth::id())
                <p class="text-sm text-[#9198a1]">you sent a friend request to {{ $pendingRequest?->sender->name }}</p>
                <form action="{{ route('requests.destroy', $pendingRequest->id) }}" method="POST" class="mt-4">
                    @csrf
                    @method('DELETE')
                    <button
                        class="rounded-lg border border-white/20 bg-[#0d1117] px-5 py-2 text-sm font-medium text-white transition hover:border-white/50">
                        cansel
                    </button>
                </form>
            @endif

            @if ($pendingRequest?->receiver_id == Auth::id())
                <p class="text-sm text-[#9198a1]">{{ $pendingRequest?->sender->name }} send a friend request</p>
                <div class="mt-4 flex flex-wrap gap-3">
                    <form action="{{ route('requests.reject', $pendingRequest->id) }}" method="POST">
                        @csrf
                        <button
                            class="rounded-lg border border-red-400/30 bg-red-500/10 px-5 py-2 text-sm font-medium text-red-200 transition hover:bg-red-500/20">
                            reject
                        </button>
                    </form>
                    <form action="{{ route('requests.accept', $pendingRequest->id) }}" method="POST">
                        @csrf
                        <button
                            class="rounded-lg border border-white/20 bg-[#0d1117] px-5 py-2 text-sm font-medium text-white transition hover:border-white/50">
                            accept
                        </button>
                    </form>
                </div>
            @endif
        </section>
    @endif

    <section class="rounded-2xl border border-white/10 bg-[#151b23] p-6 shadow-lg">
        <div class="mb-6 flex items-center justify-between gap-4">
            <h3 class="text-xl font-bold text-white">Posts</h3>
            <p class="text-sm text-[#9198a1]">{{ count($posts) }} total</p>
        </div>

        <div class="flex flex-col gap-6">
            @forelse ($posts as $p)
                <article class="overflow-hidden rounded-2xl border border-white/10 bg-[#0d1117]">
                    <div class="border-b border-white/10 bg-[#151b23] px-5 py-4">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="text-sm font-medium text-white">{{ $user->name }}</p>
                                <p class="mt-1 text-xs text-[#9198a1]">likes : {{ count($p->likes) }}</p>
                            </div>

                            <div class="flex flex-wrap gap-2">
                                @can('hide', $p)
                                    <form
                                        action="{{ auth()->user()->role === 'Admin' ? route('posts.hide', $p->id) : route('admin.post.hide', $p->id) }}"
                                        method='POST'>
                                        @csrf
                                        <button
                                            class="rounded-lg border border-white/20 bg-[#0d1117] px-4 py-2 text-sm font-medium text-white transition hover:border-white/50">
                                            hide
                                        </button>
                                    </form>
                                @endcan

                                @if ($user->id === $p->user_id)
                                    <a href="{{ route('posts.edit', $p->id) }}"
                                        class="rounded-lg border border-white/20 bg-[#0d1117] px-4 py-2 text-sm font-medium text-white transition hover:border-white/50">
                                        update
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="px-5 py-5">
                        @if (count($p?->images))
                            <div class="mb-4 grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                                @foreach($p?->images as $image)
                                    <img src="{{ asset('storage/' . $image?->path) }}" alt="post image"
                                        class="h-48 w-full rounded-xl border border-white/10 bg-[#151b23] object-cover">
                                @endforeach
                            </div>
                        @endif

                        <p class="whitespace-pre-line text-sm leading-7 text-white">{{ $p->content }}</p>

                        <div class="mt-4 flex flex-wrap gap-3 text-sm text-[#9198a1]">
                            <span class="rounded-full border border-white/10 bg-[#151b23] px-3 py-1">likes : {{ count($p->likes) }}</span>
                            <span class="rounded-full border border-white/10 bg-[#151b23] px-3 py-1">is hidden : {{ $p->is_hidden ? 'true' : 'false' }}</span>
                        </div>

                        <div class="mt-5 flex flex-col gap-2">
                            @foreach ($p->comments as $c)
                                <div class="rounded-lg border border-white/10 bg-[#151b23] px-4 py-3 text-sm text-[#9198a1]">
                                    -- {{ $c->content }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                </article>
            @empty
                <div class="rounded-xl border border-dashed border-white/15 bg-[#0d1117] px-4 py-5 text-sm text-[#9198a1]">
                    no posts yet
                </div>
            @endforelse
        </div>
    </section>
</section>
@endsection
