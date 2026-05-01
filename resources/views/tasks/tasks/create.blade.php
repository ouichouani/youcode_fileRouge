@extends('components.layout')

@section('title')
    CREATE TASK
@endsection

@section('nav')
        <a href='{{ route('dashboard') }}'>board</a>
        <a href='{{ route('habits.index') }}'>habits</a>
        <a href='{{ route('tasks.index') }}'>tasks</a>
        <a href='{{ route('categories.index') }}'>categories</a>
        <a href='{{ route('logs.index') }}'>historie</a>
@endsection


@section('content')
<section class="mx-auto w-full max-w-4xl py-6">
    <div class="mb-6 rounded-2xl border border-white/10 bg-[#151b23] px-6 py-5 shadow-lg">
        <h2 class="text-xl font-bold tracking-wide text-white">Create a new task</h2>
        <p class="mt-2 text-sm text-[#9198a1]">
            Fill in the details below to add a task to your board.
        </p>
    </div>

    <form action="{{ route('tasks.store') }}" method="POST"
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

            <div class="md:col-span-2">
                <label for="description" class="flex flex-col gap-2">
                    <span class="text-sm font-medium text-white">Description</span>
                    <textarea id="description" name="description" rows="7" placeholder="task or habit description"
                        class="p-2 px-3 bg-[#0d1117] border border-solid border-white/20 rounded-lg text-white placeholder:text-[#9198a1] focus:bg-transparent focus:outline-blue-500 focus:outline-2">{{ old('description') }}</textarea>
                </label>
                @error('description')
                    <div class="mt-2 text-sm text-red-400">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label for="difficulty" class="flex flex-col gap-2">
                    <span class="text-sm font-medium text-white">Difficulty</span>
                    <select id="difficulty" name="difficulty" required
                        class="p-2 px-3 bg-[#0d1117] border border-solid border-white/20 rounded-lg text-white focus:bg-transparent focus:outline-blue-500 focus:outline-2">
                        <option class="bg-[#151b23] text-white" value="xxs" @selected(old('difficulty') === 'xxs')>xxs</option>
                        <option class="bg-[#151b23] text-white" value="xs" @selected(old('difficulty') === 'xs')>xs</option>
                        <option class="bg-[#151b23] text-white" value="s" @selected(old('difficulty') === 's')>s</option>
                        <option class="bg-[#151b23] text-white" value="m" @selected(old('difficulty', 'm') === 'm')>m</option>
                        <option class="bg-[#151b23] text-white" value="l" @selected(old('difficulty') === 'l')>l</option>
                        <option class="bg-[#151b23] text-white" value="xl" @selected(old('difficulty') === 'xl')>xl</option>
                        <option class="bg-[#151b23] text-white" value="xxl" @selected(old('difficulty') === 'xxl')>xxl</option>
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
                        <option class="bg-[#151b23] text-white" value="xxs" @selected(old('priority') === 'xxs')>xxs</option>
                        <option class="bg-[#151b23] text-white" value="xs" @selected(old('priority') === 'xs')>xs</option>
                        <option class="bg-[#151b23] text-white" value="s" @selected(old('priority') === 's')>s</option>
                        <option class="bg-[#151b23] text-white" value="m" @selected(old('priority', 'm') === 'm')>m</option>
                        <option class="bg-[#151b23] text-white" value="l" @selected(old('priority') === 'l')>l</option>
                        <option class="bg-[#151b23] text-white" value="xl" @selected(old('priority') === 'xl')>xl</option>
                        <option class="bg-[#151b23] text-white" value="xxl" @selected(old('priority') === 'xxl')>xxl</option>
                    </select>
                </label>
                @error('priority')
                    <div class="mt-2 text-sm text-red-400">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label for="deadline" class="flex flex-col gap-2">
                    <span class="text-sm font-medium text-white">Deadline</span>
                    <input id="deadline" type="date" name="deadline" value="{{ old('deadline') }}" required
                        class="p-2 px-3 bg-[#0d1117] border border-solid border-white/20 rounded-lg text-white focus:bg-transparent focus:outline-blue-500 focus:outline-2">
                </label>
                @error('deadline')
                    <div class="mt-2 text-sm text-red-400">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label for="category_id" class="flex flex-col gap-2">
                    <span class="text-sm font-medium text-white">Category</span>
                    @if (empty($categories))
                        <div
                            class="rounded-lg border border-dashed border-white/20 bg-[#0d1117] px-3 py-2 text-sm text-[#9198a1]">
                            no category created to select from
                        </div>
                    @else
                        <select id="category_id" name="category_id"
                            class="p-2 px-3 bg-[#0d1117] border border-solid border-white/20 rounded-lg text-white focus:bg-transparent focus:outline-blue-500 focus:outline-2">
                            @foreach ($categories as $category)
                                <option class="bg-[#151b23] text-white" value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                                    {{ $category->title }}
                                </option>
                            @endforeach
                        </select>
                    @endif
                </label>
                @error('category_id')
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
