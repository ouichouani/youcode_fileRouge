@extends('components.layout')

@section('title')
    CREATE POST
@endsection

@section('content')
@php
    $types = ['Question', 'History', 'Encouragement'];
    $visibilities = ['public', 'private', 'friends'];
@endphp

<section class="mx-auto w-full max-w-4xl">
    <div class="mb-6 rounded-2xl border border-white/10 bg-[#151b23] px-6 py-5 shadow-lg">
        <h2 class="text-xl font-bold tracking-wide text-white">Create a new post</h2>
        <p class="mt-2 text-sm text-[#9198a1]">
            Fill in the details below to publish a post with the same clean workflow used across the app.
        </p>
    </div>

    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data"
        class="rounded-2xl border border-white/10 bg-[#151b23] p-6 shadow-lg">
        @csrf

        @if ($errors->any())
            <div class="mb-5 rounded-xl border border-red-400/20 bg-red-500/10 px-4 py-3 text-sm text-red-200">
                There are a few things to fix before this post can be published.
            </div>
        @endif

        <div class="grid gap-5 md:grid-cols-2">
            <div class="md:col-span-2">
                <label for="content" class="flex flex-col gap-2">
                    <span class="text-sm font-medium text-white">Post content</span>
                    <textarea name="content" id="content" rows="8" placeholder="write your post here"
                        class="p-2 px-3 bg-[#0d1117] border border-solid border-white/20 rounded-lg text-white placeholder:text-[#9198a1] focus:bg-transparent focus:outline-blue-500 focus:outline-2">{{ old('content') }}</textarea>
                </label>
                <p class="mt-2 text-sm text-[#9198a1]">You can leave this empty if you upload images instead.</p>
                @error('content')
                    <div class="mt-2 text-sm text-red-400">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label for="type" class="flex flex-col gap-2">
                    <span class="text-sm font-medium text-white">Post type</span>
                    <select name="type" id="type" required
                        class="p-2 px-3 bg-[#0d1117] border border-solid border-white/20 rounded-lg text-white focus:bg-transparent focus:outline-blue-500 focus:outline-2">
                        @foreach ($types as $type)
                            <option class="bg-[#151b23] text-white" value="{{ $type }}" @selected(old('type', 'Question') === $type)>{{ $type }}</option>
                        @endforeach
                    </select>
                </label>
                @error('type')
                    <div class="mt-2 text-sm text-red-400">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label for="visibility" class="flex flex-col gap-2">
                    <span class="text-sm font-medium text-white">Visibility</span>
                    <select name="visibility" id="visibility" required
                        class="p-2 px-3 bg-[#0d1117] border border-solid border-white/20 rounded-lg text-white focus:bg-transparent focus:outline-blue-500 focus:outline-2">
                        <option class="bg-[#151b23] text-white" value="" disabled {{ old('visibility') ? '' : 'selected' }}>
                            Choose visibility
                        </option>
                        @foreach ($visibilities as $visibility)
                            <option class="bg-[#151b23] text-white" value="{{ $visibility }}" @selected(old('visibility') === $visibility)>{{ $visibility }}</option>
                        @endforeach
                    </select>
                </label>
                @error('visibility')
                    <div class="mt-2 text-sm text-red-400">{{ $message }}</div>
                @enderror
            </div>

            <div class="md:col-span-2">
                <label for="images" class="flex flex-col gap-2">
                    <span class="text-sm font-medium text-white">Images</span>
                    <input type="file" name="images[]" id="images" accept="image/*" multiple
                        class="p-2 px-3 bg-[#0d1117] border border-solid border-white/20 rounded-lg text-sm text-white file:mr-4 file:rounded-md file:border-0 file:bg-[#151b23] file:px-3 file:py-2 file:text-sm file:text-white">
                </label>
                <p class="mt-2 text-sm text-[#9198a1]">PNG, JPG, JPEG, or GIF up to 2MB each.</p>
                @error('images')
                    <div class="mt-2 text-sm text-red-400">{{ $message }}</div>
                @enderror
                @error('images.*')
                    <div class="mt-2 text-sm text-red-400">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mt-8 flex justify-end gap-3">
            <a href="{{ route('posts.index') }}"
                class="rounded-lg border border-white/10 bg-[#151b23] px-6 py-2 text-sm font-medium text-[#9198a1] transition hover:border-white/20 hover:text-white">
                back
            </a>
            <button
                class="rounded-lg border border-white/20 bg-[#0d1117] px-6 py-2 text-sm font-medium text-white transition hover:bg-green-500/20">
                create
            </button>
        </div>
    </form>
</section>
@endsection
