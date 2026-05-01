@extends('components.layout')

@section('title')
    SHOW HABIT
@endsection

@section('nav')
    <a href='{{ route('dashboard') }}'>board</a>
    <a href='{{ route('habits.index') }}'>habits</a>
    <a href='{{ route('tasks.index') }}'>tasks</a>
    <a href='{{ route('categories.index') }}'>categories</a>
    <a href='{{ route('logs.index') }}'>historie</a>
@endsection

@section('content')
    <section class="mx-auto w-full max-w-5xl py-6">
        <div class="mb-6 rounded-2xl border border-white/10 bg-[#151b23] p-6 shadow-lg">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
                <div class="flex-1">
                    @if ($habit->category_id != null)
                        <div class="mb-4 flex items-center gap-3 mt-2">
                            <div
                                class="h-3 w-3 rounded-full border border-white/70 bg-[{{ $habit->category?->color ?? '#9198a1' }}]">
                            </div>

                            <a href="{{ route('categories.show', $habit->category?->id) }}">
                                <p class="text-lg text-[#9198a1]">{{ $habit->category?->title }}</p>
                            </a>
                        </div>
                    @endif
                    <h2 class="text-3xl font-bold text-white">{{ $habit->title }}</h2>


                    <p class="mt-4 max-w-3xl text-sm leading-7 text-[#9198a1]">
                        {{ $habit->description ?: 'No description' }}
                    </p>
                </div>

                <div class="flex gap-3">
                    <a href='{{ route('habits.edit', $habit->id) }}'
                        class="rounded-lg border border-white/20 bg-[#0d1117] px-5 py-2 text-sm font-medium text-white transition hover:border-white/50">
                        update
                    </a>
                    <form action='{{ route('habits.destroy', $habit->id) }}' method='post'>
                        @method('DELETE')
                        @csrf
                        <button
                            class="rounded-lg border border-red-400/30 bg-red-500/10 px-5 py-2 text-sm font-medium text-red-200 transition hover:bg-red-500/20">
                            delete
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
            <section class="rounded-2xl border border-white/10 bg-[#151b23] p-6 shadow-lg">
                <h3 class="mb-5 text-xl font-bold text-white">Overview</h3>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="rounded-xl border border-white/10 bg-[#0d1117] p-4">
                        <p class="text-xs uppercase tracking-[0.2em] text-[#9198a1]">Streaks</p>
                        <p class="mt-2 text-lg font-semibold text-white">{{ $habit->streaks }}</p>
                    </div>
                    <div class="rounded-xl border border-white/10 bg-[#0d1117] p-4">
                        <p class="text-xs uppercase tracking-[0.2em] text-[#9198a1]">Category</p>
                        <p class="mt-2 text-lg font-semibold text-white">{{ $habit->category?->title ?? 'No category' }}
                        </p>
                    </div>
                    <div class="rounded-xl border border-white/10 bg-[#0d1117] p-4">
                        <p class="text-xs uppercase tracking-[0.2em] text-[#9198a1]">Priority</p>
                        <p class="mt-2 text-lg font-semibold text-white">{{ $habit->priority }}</p>
                    </div>
                    <div class="rounded-xl border border-white/10 bg-[#0d1117] p-4">
                        <p class="text-xs uppercase tracking-[0.2em] text-[#9198a1]">Difficulty</p>
                        <p class="mt-2 text-lg font-semibold text-white">{{ $habit->difficulty }}</p>
                    </div>
                </div>
            </section>

            <section class="rounded-2xl border border-white/10 bg-[#151b23] p-6 shadow-lg">
                <h3 class="mb-5 text-xl font-bold text-white">More info</h3>
                <div class="flex flex-col gap-4">
                    <div class="rounded-xl border border-white/10 bg-[#0d1117] p-4">
                        <p class="text-xs uppercase tracking-[0.2em] text-[#9198a1]">Deadline</p>
                        <p class="mt-2 text-lg font-semibold text-white">
                            {{ $habit->deadline ? $habit->deadline->format('Y-m-d H:i') : 'No deadline' }}
                        </p>
                    </div>

                    <div class="rounded-xl border border-white/10 bg-[#0d1117] p-4">
                        <p class="text-xs uppercase tracking-[0.2em] text-[#9198a1]">Frequency</p>
                        <p class="mt-2 text-lg font-semibold text-white">
                            @if (!empty($habit->frequency) && is_array($habit->frequency))
                                {{ implode(', ', $habit->frequency) }}
                            @else
                                No frequency set
                            @endif
                        </p>
                    </div>

                </div>
            </section>
        </div>
    </section>
@endsection
