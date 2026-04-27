@extends('components.layout')


@section('title')
    EDIT TASK
@endsection

@section('nav')
        <a href='{{ route('dashboard') }}'>board</a>
        <a href='{{ route('habits.index') }}'>habits</a>
        <a href='{{ route('tasks.index') }}'>tasks</a>
        <a href='{{ route('logs.index') }}'>historie</a>
        <a href='{{ route('categories.index') }}'>categories</a>
@endsection


@section('content')


@php
    $freq = $task->frequency ?? [];
@endphp


<section class="mx-auto w-full max-w-4xl py-6">
    <div class="mb-6 rounded-2xl border border-white/10 bg-[#151b23] px-6 py-5 shadow-lg">
        <h2 class="text-xl font-bold tracking-wide text-white">Edit task</h2>
        <p class="mt-2 text-sm text-[#9198a1]">
            Update the task details with the same visual style as the create form.
        </p>
    </div>

    <form action="{{ route('tasks.update' , $task->id) }}" method="POST"
        class="rounded-2xl border border-white/10 bg-[#151b23] p-6 shadow-lg">
        @csrf
        @method('PUT')

        <div class="grid gap-5 md:grid-cols-2">
            <div class="md:col-span-2">
                <label for="title" class="flex flex-col gap-2">
                    <span class="text-sm font-medium text-white">Title</span>
                    <input id="title" type="text" name="title" placeholder="title" value="{{ $task->title }}"
                        class="p-2 px-3 bg-[#0d1117] border border-solid border-white/20 rounded-lg text-white placeholder:text-[#9198a1] focus:bg-transparent focus:outline-blue-500 focus:outline-2">
                </label>
                @error('frequency')
                    <div class="mt-2 text-sm text-red-400">{{ $message }}</div>
                @enderror
            </div>

            <div class="md:col-span-2">
                <label for="description" class="flex flex-col gap-2">
                    <span class="text-sm font-medium text-white">Description</span>
                    <textarea id="description" name="description" rows="7" placeholder="task or habit description"
                        class="p-2 px-3 bg-[#0d1117] border border-solid border-white/20 rounded-lg text-white placeholder:text-[#9198a1] focus:bg-transparent focus:outline-blue-500 focus:outline-2">{{ $task->description }}</textarea>
                </label>
                @error('description')
                    <div class="mt-2 text-sm text-red-400">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label for="difficulty" class="flex flex-col gap-2">
                    <span class="text-sm font-medium text-white">Difficulty</span>
                    <select id="difficulty" name="difficulty"
                        class="p-2 px-3 bg-[#0d1117] border border-solid border-white/20 rounded-lg text-white focus:bg-transparent focus:outline-blue-500 focus:outline-2">
                        <option class="bg-[#151b23] text-white" @if ($task->difficulty === 'xxs') selected @endif value="xxs">xxs</option>
                        <option class="bg-[#151b23] text-white" @if ($task->difficulty === 'xs') selected @endif value="xs">xs</option>
                        <option class="bg-[#151b23] text-white" @if ($task->difficulty === 's') selected @endif value="s">s</option>
                        <option class="bg-[#151b23] text-white" @if ($task->difficulty === 'm') selected @endif value="m">m</option>
                        <option class="bg-[#151b23] text-white" @if ($task->difficulty === 'l') selected @endif value="l">l</option>
                        <option class="bg-[#151b23] text-white" @if ($task->difficulty === 'xl') selected @endif value="xl">xl</option>
                        <option class="bg-[#151b23] text-white" @if ($task->difficulty === 'xxl') selected @endif value="xxl">xxl</option>
                    </select>
                </label>
                @error('difficulty')
                    <div class="mt-2 text-sm text-red-400">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label for="priority" class="flex flex-col gap-2">
                    <span class="text-sm font-medium text-white">Priority</span>
                    <select id="priority" name="priority" required
                        class="p-2 px-3 bg-[#0d1117] border border-solid border-white/20 rounded-lg text-white focus:bg-transparent focus:outline-blue-500 focus:outline-2">
                        <option class="bg-[#151b23] text-white" @if ($task->priority == 'xxs') selected @endif value="xxs">xxs</option>
                        <option class="bg-[#151b23] text-white" @if ($task->priority == 'xs') selected @endif value="xs">xs</option>
                        <option class="bg-[#151b23] text-white" @if ($task->priority == 's') selected @endif value="s">s</option>
                        <option class="bg-[#151b23] text-white" @if ($task->priority == 'm') selected @endif value="m">m</option>
                        <option class="bg-[#151b23] text-white" @if ($task->priority == 'l') selected @endif value="l">l</option>
                        <option class="bg-[#151b23] text-white" @if ($task->priority == 'xl') selected @endif value="xl">xl</option>
                        <option class="bg-[#151b23] text-white" @if ($task->priority == 'xxl') selected @endif value="xxl">xxl</option>
                    </select>
                </label>
                @error('priority')
                    <div class="mt-2 text-sm text-red-400">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label for="deadline" class="flex flex-col gap-2">
                    <span class="text-sm font-medium text-white">Deadline</span>
                    <input id="deadline" type="date" name="deadline" value="{{ $task->deadline->format('Y-m-d') }}" required
                        class="p-2 px-3 bg-[#0d1117] border border-solid border-white/20 rounded-lg text-white focus:bg-transparent focus:outline-blue-500 focus:outline-2">
                </label>
                @error('deadline')
                    <div class="mt-2 text-sm text-red-400">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label for="category_id" class="flex flex-col gap-2">
                    <span class="text-sm font-medium text-white">Category</span>
                    <select id="category_id" name="category_id"
                        class="p-2 px-3 bg-[#0d1117] border border-solid border-white/20 rounded-lg text-white focus:bg-transparent focus:outline-blue-500 focus:outline-2">
                        <option class="bg-[#151b23] text-white" value=""> no category </option>
                        @forelse ($categories as $category)
                            <option class="bg-[#151b23] text-white" value="{{ $category->id }}" @if ($category->id == $task?->category_id) selected @endif>
                                {{ $category->title }}</option>
                        @empty
                            <option class="bg-[#151b23] text-white" value=""> no category created </option>
                        @endforelse
                    </select>
                </label>
                @error('category_id')
                    <div class="mt-2 text-sm text-red-400">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mt-8 flex justify-end">
            <button
                class="rounded-lg border border-white/20 bg-[#0d1117] px-6 py-2 text-sm font-medium text-white transition hover:bg-green-500/20">
                update
            </button>
        </div>

    </form>
</section>
@endsection
