@extends('components.layout')

@section('title')
    CREATE CATEGORY
@endsection

@section('nav')
        <a href='{{ route('dashboard') }}'>board</a>
        <a href='{{ route('habits.index') }}'>habits</a>
        <a href='{{ route('tasks.index') }}'>tasks</a>
        <a href='{{ route('logs.index') }}'>historie</a>
        <a href='{{ route('categories.index') }}'>categories</a>
@endsection

@section('content')
<section class="mx-auto w-full max-w-4xl px-4 py-6">
    <div class="mb-6 rounded-2xl border border-white/10 bg-[#151b23] px-6 py-5 shadow-lg">
        <h2 class="text-xl font-bold tracking-wide text-white">Create a new category</h2>
        <p class="mt-2 text-sm text-[#9198a1]">
            Add a category with the same clean form style used across tasks and habits.
        </p>
    </div>

    <form action="{{ route('categories.store') }}" method="POST"
        class="rounded-2xl border border-white/10 bg-[#151b23] p-6 shadow-lg">
        @csrf

        <div class="grid gap-5 md:grid-cols-2">
            <div class="md:col-span-2">
                <label for="title" class="flex flex-col gap-2">
                    <span class="text-sm font-medium text-white">Title</span>
                    <input id="title" type="text" name="title" value="{{ old('title') }}" placeholder="title" required
                        class="p-2 px-3 bg-[#0d1117] border border-solid border-white/20 rounded-lg text-white placeholder:text-[#9198a1] focus:bg-transparent focus:outline-blue-500 focus:outline-2">
                </label>
                @error('title')
                    <div class="mt-2 text-sm text-red-400">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label for="color" class="flex flex-col gap-2">
                    <span class="text-sm font-medium text-white">Color</span>
                    <div class="flex items-center gap-3 rounded-lg border border-white/20 bg-[#0d1117] px-3 py-2">
                        <input id="color" type="color" name="color" value="{{ old('color', '#3b82f6') }}"
                            class="h-5 w-5 cursor-pointer rounded-full border border-white/20 bg-transparent">
                        <span class="text-sm text-[#9198a1]">Choose the category color</span>
                    </div>
                </label>
                @error('color')
                    <div class="mt-2 text-sm text-red-400">{{ $message }}</div>
                @enderror
            </div>

            @can('accessGlobalCategories' , App\Models\Category::class)
            <div>
                <div class="flex h-full flex-col justify-end">
                    <label for="is_global"
                        class="flex items-center gap-3 rounded-lg border border-white/20 bg-[#0d1117] px-4 py-2 text-sm text-white">
                        <input type="checkbox" name="is_global" id="is_global" class="accent-blue-500" @checked(old('is_global'))>
                        <span>set as global categories</span>
                    </label>
                </div>
            </div>
            @endcan

            <div class="md:col-span-2">
                <label for="description" class="flex flex-col gap-2">
                    <span class="text-sm font-medium text-white">Description</span>
                    <textarea id="description" name="description" placeholder="description" rows="7"
                        class="p-2 px-3 bg-[#0d1117] border border-solid border-white/20 rounded-lg text-white placeholder:text-[#9198a1] focus:bg-transparent focus:outline-blue-500 focus:outline-2">{{ old('description') }}</textarea>
                </label>
                @error('description')
                    <div class="mt-2 text-sm text-red-400">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mt-8 flex justify-end">
            <button
                class="rounded-lg border border-white/20 bg-[#0d1117] px-6 py-2 text-sm font-medium text-white transition hover:bg-green-500/20">
                create
            </button>
        </div>
    </form>
</section>

@endsection
