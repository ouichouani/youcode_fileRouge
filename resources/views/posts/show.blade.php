@extends('components.layout')

@section('title')
    POSTS
@endsection

@section('nav')
    <a href='{{ route('posts.create') }}'>create post</a>
    <a href='{{ route('posts.index') }}'>all posts</a>
@endsection

@section('content')
    <section class="mx-auto w-full max-w-6xl px-4 py-6">
        <div class="grid gap-6 lg:grid-cols-[minmax(0,2fr)_minmax(320px,1fr)]">
            <article class="overflow-hidden rounded-2xl border border-white/10 shadow-lg">
                <div class="bg-[#151b23] px-6 py-4">
                    <div class="flex items-start justify-between gap-4">
                        <a href="{{ route('users.show', $post->user->id) }}" class="flex items-center gap-4">
                            <img class="h-14 w-14 rounded-full border border-white/20 bg-[#0d1117] object-cover"
                                src="{{ asset('storage/' . $post->user->image?->path) }}" alt="{{ $post->user->name }}">
                            <div>
                                <h2 class="text-lg font-semibold text-white">{{ $post->user->name }}</h2>
                                <p class="text-sm text-[#9198a1]">{{ $post->created_at->diffForHumans() }}</p>
                            </div>
                        </a>

                        <div class="rounded-full border border-white/10 bg-[#0d1117] px-3 py-1 text-xs uppercase tracking-[0.2em] text-[#9198a1]">
                            {{ $post->type }}
                        </div>
                    </div>
                </div>

                <div class="bg-[#151b23] px-6 pb-6">
                    <div class="rounded-xl bg-[#151b23] py-2">
                        <p class="whitespace-pre-line text-sm leading-7 text-white">{{ $post->content }}</p>
                    </div>

                    @if (count($post->images))
                        <div class="mt-5 grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                            @foreach ($post->images as $image)
                                <img class="h-56 w-full rounded-xl border border-white/10 bg-[#0d1117] object-cover"
                                    src="{{ asset('storage/' . $image->path) }}" alt="post image">
                            @endforeach
                        </div>
                    @endif

                    <div class="mt-6 flex flex-wrap gap-3">
                        <a href="{{ route('posts.edit' , $post->id) }}"
                            class="rounded-lg border border-white/20 bg-[#0d1117] px-5 py-2 text-sm font-medium text-white transition hover:border-white/50">
                            update
                        </a>

                        <form action="{{ route('posts.destroy' ,  $post->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button
                                class="rounded-lg border border-red-400/30 bg-red-500/10 px-5 py-2 text-sm font-medium text-red-200 transition hover:bg-red-500/20">
                                delete
                            </button>
                        </form>

                        <form action="{{ route('likes.save') }}" method="POST">
                            @csrf
                            <input type="hidden" name="post_id" value="{{ $post->id }}">
                            <button
                                class="rounded-lg border border-white/20 bg-[#0d1117] px-5 py-2 text-sm font-medium text-white transition hover:border-white/50">
                                {{ $post->likes->contains('user_id', auth()->id()) ? 'unlike' : 'like' }}
                            </button>
                        </form>
                    </div>
                </div>
            </article>

            <aside class="flex flex-col gap-6">
                <section class="rounded-2xl border border-white/10 bg-[#151b23] p-6 shadow-lg">
                    <h3 class="mb-5 text-xl font-bold text-white">Overview</h3>
                    <div class="grid gap-4">
                        <div class="rounded-xl border border-white/10 bg-[#0d1117] p-4">
                            <p class="text-xs uppercase tracking-[0.2em] text-[#9198a1]">Likes</p>
                            <p class="mt-2 text-lg font-semibold text-white">{{ count($likes) }}</p>
                        </div>
                        <div class="rounded-xl border border-white/10 bg-[#0d1117] p-4">
                            <p class="text-xs uppercase tracking-[0.2em] text-[#9198a1]">Comments</p>
                            <p class="mt-2 text-lg font-semibold text-white">{{ count($comments) }}</p>
                        </div>
                        <div class="rounded-xl border border-white/10 bg-[#0d1117] p-4">
                            <p class="text-xs uppercase tracking-[0.2em] text-[#9198a1]">Visibility</p>
                            <p class="mt-2 text-lg font-semibold text-white">{{ $post->visibility }}</p>
                        </div>
                    </div>
                </section>

                <section class="rounded-2xl border border-white/10 bg-[#151b23] p-6 shadow-lg">
                    <h3 class="mb-5 text-xl font-bold text-white">Add comment</h3>
                    <form action="{{ route('comments.store') }}" method="POST" class="flex flex-col gap-4">
                        @csrf
                        <textarea name="content" rows="4" placeholder="write your comment"
                            class="rounded-lg border border-white/20 bg-[#0d1117] p-3 text-sm text-white placeholder:text-[#9198a1] focus:bg-transparent focus:outline-blue-500 focus:outline-2"></textarea>
                        <input type="hidden" name="post_id" value="{{ $post->id }}">
                        <button
                            class="rounded-lg border border-white/20 bg-[#0d1117] px-5 py-2 text-sm font-medium text-white transition hover:border-white/50">
                            send
                        </button>
                    </form>
                </section>
            </aside>
        </div>

        <section class="mt-6 rounded-2xl border border-white/10 bg-[#151b23] p-6 shadow-lg">
            <div class="mb-6 flex items-center justify-between gap-4">
                <h3 class="text-xl font-bold text-white">Comments</h3>
                <p class="text-sm text-[#9198a1]">{{ count($comments) }} total</p>
            </div>

            <div class="flex flex-col gap-4">
                @forelse ($comments as $c)
                    <article class="rounded-xl border border-white/10 bg-[#0d1117] p-4">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex items-start gap-3">
                                <img class="h-10 w-10 rounded-full border border-white/20 bg-[#151b23] object-cover"
                                    src="{{ asset('storage/' . $c->user?->image?->path) }}" alt="{{ $c->user?->name }}">
                                <div>
                                    <p class="font-medium text-white">{{ $c->user?->name ?? 'user' }}</p>
                                    <p class="mt-1 text-sm leading-6 text-[#9198a1]">{{ $c->content }}</p>
                                    <p class="mt-2 text-xs text-[#9198a1]">{{ $c->created_at->diffForHumans() }}</p>
                                </div>
                            </div>

                            <form action="{{ route('comments.destroy' , $c->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="text-sm text-red-300 transition hover:text-red-200">delete</button>
                            </form>
                        </div>
                    </article>
                @empty
                    <div class="rounded-xl border border-dashed border-white/15 bg-[#0d1117] px-4 py-5 text-sm text-[#9198a1]">
                        no comments
                    </div>
                @endforelse
            </div>
        </section>
    </section>
@endsection
