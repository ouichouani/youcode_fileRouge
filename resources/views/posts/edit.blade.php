@extends('components.layout')

@section('title')
    EDIT POST
@endsection

@section('nav')
    <a href='{{ route('posts.create') }}'>create post</a>
    <a href='{{ route('posts.index') }}'>all posts</a>
@endsection

@section('content')
@php
    $types = ['Question', 'History', 'Encouragement'];
    $visibilities = ['public', 'private', 'friends'];
@endphp

<section class="mx-auto w-full max-w-6xl px-4 py-6">
    <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_minmax(0,1fr)]">
        <section class="rounded-2xl border border-white/10 bg-[#151b23] p-6 shadow-lg">
            <div class="rounded-full border border-white/10 bg-[#0d1117] px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-[#9198a1] w-fit">
                Refine post
            </div>
            <h2 class="mt-5 text-4xl font-bold text-white">Update the post without changing its voice.</h2>
            <p class="mt-4 max-w-2xl text-sm leading-7 text-[#9198a1]">
                Adjust the message, change the type, or update visibility while keeping the current post structure.
            </p>

            <div class="mt-6 rounded-2xl border border-white/10 bg-[#0d1117] p-5">
                <div class="rounded-full border border-white/10 bg-[#151b23] px-3 py-1 text-xs uppercase tracking-[0.2em] text-[#9198a1] w-fit">
                    Current snapshot
                </div>
                <p class="mt-4 whitespace-pre-line text-sm leading-7 text-white">
                    {{ $post->content ?: 'This post currently relies on images or a very short message.' }}
                </p>
                <div class="mt-4 flex flex-wrap gap-3 text-sm text-[#9198a1]">
                    <span class="rounded-full border border-white/10 bg-[#151b23] px-3 py-1">Type: {{ $post->type ?? 'Not set' }}</span>
                    <span class="rounded-full border border-white/10 bg-[#151b23] px-3 py-1">Visibility: {{ ucfirst($post->visibility ?? 'not set') }}</span>
                    <span class="rounded-full border border-white/10 bg-[#151b23] px-3 py-1">Post #{{ $post->id }}</span>
                </div>
            </div>
        </section>

        <section class="rounded-2xl border border-white/10 bg-[#151b23] p-6 shadow-lg">
            <h2 class="text-2xl font-bold text-white">Update post</h2>
            <p class="mt-2 text-sm text-[#9198a1]">Make the changes you want, then save the updated version.</p>

            @if ($errors->any())
                <div class="mt-5 rounded-xl border border-red-400/20 bg-red-500/10 px-4 py-3 text-sm text-red-200">
                    A few fields still need attention before this update can be saved.
                </div>
            @endif

            <form action="{{ route('posts.update', $post) }}" method="POST" enctype="multipart/form-data" class="mt-6 flex flex-col gap-5">
                @csrf
                @method('PUT')

                <div>
                    <label class="mb-2 block text-sm font-medium text-white" for="content">Post content</label>
                    <textarea name="content" id="content" rows="8" placeholder="Refresh the message, sharpen the story, or ask a better question."
                        class="w-full rounded-lg border border-white/20 bg-[#0d1117] p-3 text-sm text-white placeholder:text-[#9198a1] focus:bg-transparent focus:outline-blue-500 focus:outline-2">{{ old('content', $post->content) }}</textarea>
                    <p class="mt-2 text-sm text-[#9198a1]">Leave this empty only if your update relies on images.</p>
                    @error('content')
                        <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-white" for="type">Post type</label>
                        <select name="type" id="type"
                            class="w-full rounded-lg border border-white/20 bg-[#0d1117] p-3 text-sm text-white focus:bg-transparent focus:outline-blue-500 focus:outline-2">
                            <option class="bg-[#151b23] text-white" value="" {{ old('type', $post->type) ? '' : 'selected' }}>Keep current type</option>
                            @foreach ($types as $type)
                                <option class="bg-[#151b23] text-white" value="{{ $type }}" {{ old('type', $post->type) === $type ? 'selected' : '' }}>{{ $type }}</option>
                            @endforeach
                        </select>
                        @error('type')
                            <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-white" for="visibility">Visibility</label>
                        <select name="visibility" id="visibility"
                            class="w-full rounded-lg border border-white/20 bg-[#0d1117] p-3 text-sm text-white focus:bg-transparent focus:outline-blue-500 focus:outline-2">
                            <option class="bg-[#151b23] text-white" value="" {{ old('visibility', $post->visibility) ? '' : 'selected' }}>Keep current visibility</option>
                            @foreach ($visibilities as $visibility)
                                <option class="bg-[#151b23] text-white" value="{{ $visibility }}" {{ old('visibility', $post->visibility) === $visibility ? 'selected' : '' }}>
                                    {{ ucfirst($visibility) }}
                                </option>
                            @endforeach
                        </select>
                        @error('visibility')
                            <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-white" for="images">Replace or add images</label>
                    <input type="file" name="images[]" id="images" accept="image/*" multiple
                        class="w-full rounded-lg border border-white/20 bg-[#0d1117] p-3 text-sm text-white file:mr-4 file:rounded-md file:border-0 file:bg-[#151b23] file:px-3 file:py-2 file:text-sm file:text-white">
                    <p class="mt-2 text-sm text-[#9198a1]">Optional. Upload images only if you want the update to include new media.</p>
                    @error('images')
                        <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                    @enderror
                    @error('images.*')
                        <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex flex-wrap gap-3 pt-2">
                    <button
                        class="rounded-lg border border-white/20 bg-[#0d1117] px-5 py-2 text-sm font-medium text-white transition hover:border-white/50">
                        save changes
                    </button>
                    <a href="{{ route('posts.index') }}"
                        class="rounded-lg border border-white/10 bg-[#151b23] px-5 py-2 text-sm font-medium text-[#9198a1] transition hover:border-white/20 hover:text-white">
                        back to posts
                    </a>
                </div>
            </form>
        </section>
    </div>
</section>
@endsection
