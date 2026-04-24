@extends('components.layout')

@section('title')
    CREATE POST
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
    <div class="grid gap-6 lg:grid-cols-[minmax(0,1.1fr)_minmax(0,0.9fr)]">
        <section class="rounded-2xl border border-white/10 bg-[#151b23] p-6 shadow-lg">
            <div class="rounded-full border border-white/10 bg-[#0d1117] px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-[#9198a1] w-fit">
                New story
            </div>
            <h2 class="mt-5 text-4xl font-bold text-white">Create a post that fits the app.</h2>
            <p class="mt-4 max-w-2xl text-sm leading-7 text-[#9198a1]">
                Share a question, a story, or some encouragement. Keep it clear, choose who can see it,
                and add images when the post needs more context.
            </p>

            <div class="mt-6 grid gap-4">
                <article class="rounded-xl border border-white/10 bg-[#0d1117] p-4">
                    <h3 class="text-sm font-semibold text-white">Choose the right tone</h3>
                    <p class="mt-2 text-sm leading-6 text-[#9198a1]">
                        Use <span class="text-white">Question</span> for discussion, <span class="text-white">History</span>
                        for storytelling, and <span class="text-white">Encouragement</span> to motivate others.
                    </p>
                </article>
                <article class="rounded-xl border border-white/10 bg-[#0d1117] p-4">
                    <h3 class="text-sm font-semibold text-white">Pick the right visibility</h3>
                    <p class="mt-2 text-sm leading-6 text-[#9198a1]">
                        Public reaches everyone, friends is more personal, and private keeps the post more limited.
                    </p>
                </article>
                <article class="rounded-xl border border-white/10 bg-[#0d1117] p-4">
                    <h3 class="text-sm font-semibold text-white">Text or images</h3>
                    <p class="mt-2 text-sm leading-6 text-[#9198a1]">
                        One meaningful paragraph or a clean set of images is enough to publish something solid.
                    </p>
                </article>
            </div>
        </section>

        <section class="rounded-2xl border border-white/10 bg-[#151b23] p-6 shadow-lg">
            <h2 class="text-2xl font-bold text-white">Create post</h2>
            <p class="mt-2 text-sm text-[#9198a1]">Fill in the fields below, then publish when everything looks right.</p>

            @if ($errors->any())
                <div class="mt-5 rounded-xl border border-red-400/20 bg-red-500/10 px-4 py-3 text-sm text-red-200">
                    There are a few things to fix before this post can be published.
                </div>
            @endif

            <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" class="mt-6 flex flex-col gap-5">
                @csrf

                <div>
                    <label class="mb-2 block text-sm font-medium text-white" for="content">Post content</label>
                    <textarea name="content" id="content" rows="8" placeholder="What do you want people to read, remember, or respond to?"
                        class="w-full rounded-lg border border-white/20 bg-[#0d1117] p-3 text-sm text-white placeholder:text-[#9198a1] focus:bg-transparent focus:outline-blue-500 focus:outline-2">{{ old('content') }}</textarea>
                    <p class="mt-2 text-sm text-[#9198a1]">You can leave this empty if you upload images instead.</p>
                    @error('content')
                        <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-white" for="type">Post type</label>
                        <select name="type" id="type" required
                            class="w-full rounded-lg border border-white/20 bg-[#0d1117] p-3 text-sm text-white focus:bg-transparent focus:outline-blue-500 focus:outline-2">
                            @foreach ($types as $type)
                                <option class="bg-[#151b23] text-white" value="{{ $type }}" @selected(old('type', 'Question') === $type)>{{ $type }}</option>
                            @endforeach
                        </select>
                        @error('type')
                            <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-white" for="visibility">Visibility</label>
                        <select name="visibility" id="visibility" required
                            class="w-full rounded-lg border border-white/20 bg-[#0d1117] p-3 text-sm text-white focus:bg-transparent focus:outline-blue-500 focus:outline-2">
                            <option class="bg-[#151b23] text-white" value="" disabled {{ old('visibility') ? '' : 'selected' }}>Choose visibility</option>
                            @foreach ($visibilities as $visibility)
                                <option class="bg-[#151b23] text-white" value="{{ $visibility }}" @selected(old('visibility') === $visibility)>{{ $visibility }}</option>
                            @endforeach
                        </select>
                        @error('visibility')
                            <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-white" for="images">Images</label>
                    <input type="file" name="images[]" id="images" accept="image/*" multiple
                        class="w-full rounded-lg border border-white/20 bg-[#0d1117] p-3 text-sm text-white file:mr-4 file:rounded-md file:border-0 file:bg-[#151b23] file:px-3 file:py-2 file:text-sm file:text-white">
                    <p class="mt-2 text-sm text-[#9198a1]">PNG, JPG, JPEG, or GIF up to 2MB each.</p>
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
                        publish post
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
