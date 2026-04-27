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

<section class="mx-auto w-full max-w-4xl">
    <div class="mb-6 rounded-2xl border border-white/10 bg-[#151b23] px-6 py-5 shadow-lg">
        <h2 class="text-xl font-bold tracking-wide text-white">Edit post</h2>
        <p class="mt-2 text-sm text-[#9198a1]">
            Update the details below and save your changes , changes will be visible to all users at the same moment.
        </p>
    </div>

    <form action="{{ route('posts.update', $post) }}" method="POST" enctype="multipart/form-data"
        class="rounded-2xl border border-white/10 bg-[#151b23] p-6 shadow-lg">
        @csrf
        @method('PUT')

        @if ($errors->any())
            <div class="mb-5 rounded-xl border border-red-400/20 bg-red-500/10 px-4 py-3 text-sm text-red-200">
                A few fields still need attention before this update can be saved.
            </div>
        @endif

        <div class="grid gap-5 md:grid-cols-2">
            <div class="md:col-span-2">
                <label for="content" class="flex flex-col gap-2">
                    <span class="text-sm font-medium text-white">Post content</span>
                    <textarea name="content" id="content" rows="8" placeholder="write your post here"
                        class="p-2 px-3 bg-[#0d1117] border border-solid border-white/20 rounded-lg text-white placeholder:text-[#9198a1] focus:bg-transparent focus:outline-blue-500 focus:outline-2">{{ old('content', $post->content) }}</textarea>
                </label>
                <p class="mt-2 text-sm text-[#9198a1]">Leave this empty only if your update relies on images.</p>
                @error('content')
                    <div class="mt-2 text-sm text-red-400">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label for="type" class="flex flex-col gap-2">
                    <span class="text-sm font-medium text-white">Post type</span>
                    <select name="type" id="type"
                        class="p-2 px-3 bg-[#0d1117] border border-solid border-white/20 rounded-lg text-white focus:bg-transparent focus:outline-blue-500 focus:outline-2">
                        <option class="bg-[#151b23] text-white" value="" {{ old('type', $post->type) ? '' : 'selected' }}>
                            Keep current type
                        </option>
                        @foreach ($types as $type)
                            <option class="bg-[#151b23] text-white" value="{{ $type }}" {{ old('type', $post->type) === $type ? 'selected' : '' }}>{{ $type }}</option>
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
                    <select name="visibility" id="visibility"
                        class="p-2 px-3 bg-[#0d1117] border border-solid border-white/20 rounded-lg text-white focus:bg-transparent focus:outline-blue-500 focus:outline-2">
                        <option class="bg-[#151b23] text-white" value="" {{ old('visibility', $post->visibility) ? '' : 'selected' }}>
                            Keep current visibility
                        </option>
                        @foreach ($visibilities as $visibility)
                            <option class="bg-[#151b23] text-white" value="{{ $visibility }}" {{ old('visibility', $post->visibility) === $visibility ? 'selected' : '' }}>
                                {{ ucfirst($visibility) }}
                            </option>
                        @endforeach
                    </select>
                </label>
                @error('visibility')
                    <div class="mt-2 text-sm text-red-400">{{ $message }}</div>
                @enderror
            </div>

            <div class="md:col-span-2">
                <label for="images" class="flex flex-col gap-2">
                    <span class="text-sm font-medium text-white">Replace or add images</span>
                    <input type="file" name="images[]" id="images" accept="image/*" multiple
                        class="p-2 px-3 bg-[#0d1117] border border-solid border-white/20 rounded-lg text-sm text-white file:mr-4 file:rounded-md file:border-0 file:bg-[#151b23] file:px-3 file:py-2 file:text-sm file:text-white">
                </label>
                <p class="mt-2 text-sm text-[#9198a1]">Optional. Upload images only if you want the update to include new media.</p>
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
                save changes
            </button>
        </div>
    </form>
</section>
@endsection
