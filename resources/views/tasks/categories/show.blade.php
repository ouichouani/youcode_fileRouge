@extends('components.layout')

@section('title')
    SHOW CATEGORY
@endsection

@section('nav')
        <a href='{{ route('dashboard') }}'>board</a>
        <a href='{{ route('habits.index') }}'>habits</a>
        <a href='{{ route('tasks.index') }}'>tasks</a>
        <a href='{{ route('logs.index') }}'>historie</a>
        <a href='{{ route('categories.index') }}'>categories</a>
@endsection

@section('content')
<section class="mx-auto w-full max-w-5xl px-4 py-6">
    <div class="mb-6 rounded-2xl border border-white/10 bg-[#151b23] p-6 shadow-lg">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
            <div class="flex-1">
                <div class="mb-4 flex items-center gap-3">
                    <div class="h-4 w-4 rounded-full border border-white/70" style="background-color: {{ $category->color }}"></div>
                    <p class="text-xs uppercase tracking-[0.3em] text-[#9198a1]">Category details</p>
                </div>

                <h2 class="text-3xl font-bold text-white">{{ $category->title }}</h2>
                <p class="mt-4 max-w-3xl text-sm leading-7 text-[#9198a1]">
                    {{ $category->description }}
                </p>
            </div>

            <div class="flex gap-3">
                <a href="{{ route('categories.edit' ,$category->id ) }}"
                    class="rounded-lg border border-white/20 bg-[#0d1117] px-5 py-2 text-sm font-medium text-white transition hover:border-white/50">
                    update
                </a>

                <form action="{{ route('categories.destroy' , $category->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
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
            <div class="mb-6 flex flex-col gap-2">
                <h3 class="text-2xl font-bold text-white">Tasks</h3>
                <p class="text-sm text-[#9198a1]">All tasks linked to this category.</p>
            </div>

            <div class="flex flex-col gap-4">
                @forelse ($tasks as $task)
                    <article class="rounded-xl border border-white/10 bg-[#0d1117] p-4">
                        <h4 class="text-lg font-semibold text-white">{{ $task->title }}</h4>
                        <p class="mt-2 text-sm leading-6 text-[#9198a1]">{{ $task->description }}</p>
                    </article>
                @empty
                    <p class="rounded-xl border border-dashed border-white/15 bg-[#0d1117] px-4 py-5 text-sm text-[#9198a1]">
                        this cat doesn't have any tasks
                    </p>
                @endforelse
            </div>
        </section>

        <section class="rounded-2xl border border-white/10 bg-[#151b23] p-6 shadow-lg">
            <div class="mb-6 flex flex-col gap-2">
                <h3 class="text-2xl font-bold text-white">Habits</h3>
                <p class="text-sm text-[#9198a1]">All habits linked to this category.</p>
            </div>

            <div class="flex flex-col gap-4">
                @forelse ($habits as $habit)
                    <article class="rounded-xl border border-white/10 bg-[#0d1117] p-4">
                        <h4 class="text-lg font-semibold text-white">{{ $habit->title }}</h4>
                        <p class="mt-2 text-sm leading-6 text-[#9198a1]">{{ $habit->description }}</p>
                    </article>
                @empty
                    <p class="rounded-xl border border-dashed border-white/15 bg-[#0d1117] px-4 py-5 text-sm text-[#9198a1]">
                        this cat doesn't have any habits
                    </p>
                @endforelse
            </div>
        </section>
    </div>
</section>
@endsection
