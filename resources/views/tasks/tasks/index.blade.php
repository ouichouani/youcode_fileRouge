@extends('components.layout')


@section('title')
    TASKS
@endsection

@section('nav')
    <a href='{{ route('dashboard') }}'>board</a>
    <a href='{{ route('habits.index') }}'>habits</a>
    <a href='{{ route('tasks.index') }}'>tasks</a>
    <a href='{{ route('logs.index') }}'>historie</a>
    <a href='{{ route('categories.index') }}'>categories</a>
@endsection


{{-- @section('content') --}}

@section('content')
    <div class="relative w-full pt-15">

        <div class="flex gap-3 absolute top-[0px] right-[0px]">
            <a class="p-2 bg-[#151b23] border border-solid border-white/30 rounded-lg transition hover:border-white/60"
                href="{{ route('tasks.create') }}">add task</a>
        </div>

        @foreach ($categories as $cat)
            <div
                class='border border-white/30 border-solid rounded-lg flex flex-col gap-15  '>

            <div class="bg-[{{ $cat->color }}]/10 p-[20px] border-b border-solid border-white/30">
                <a href="{{ route('categories.show', $cat->id) }}" class="flex gap-3 items-center w-fit">
                    <div class='bg-[{{ $cat->color }}] rounded-full w-[20px] h-[20px]'></div>
                    <h1 class="text-3xl font-bold">{{ $cat->title }}</h1>
                </a>
            </div>

                <section class="flex flex-col gap-7 p-[20px]">
                    @forelse ($cat->tasks as $task)
                        <div class="flex flex-col gap-3">
                            <form class="flex gap-3" action="{{ route('tasks.done', $task->id) }}" method='POST'>
                                @csrf
                                <input type="checkbox" class="task_done cursor-pointer" {{ $task->done ? 'checked' : '' }}>
                                <a href='{{ route('tasks.show', $task->id) }}' class='flex items-center gap-2'>
                                    <p class="font-bold text-lg {{ $task->done ? 'line-through' : '' }}">
                                        {{ $task->title }}
                                    </p>
                                </a>
                            </form>
                            <div class="flex">
                                <span class="text-[#9198a1] pl-[10px]">&emsp; priority : {{ $task->priority }} </span>
                                <span class="text-[#9198a1] pl-[10px]">&emsp; difficulty : {{ $task->difficulty }} </span>
                                <span class="text-[#9198a1] pl-[10px]" title='deadline'>&emsp; {{ $task->deadline }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <p>no tasks for this cat</p>
                    @endforelse
                </section>

            </div>
            <br>
        @endforeach
    </div>
@endsection

{{-- 

@forelse ($tasks as $task )
<div style="max-width: 700px; margin: 30px auto; font-family: Arial, sans-serif;">
    <h1>Task Details</h1>

    <div style="padding: 20px; border: 1px solid #ddd; border-radius: 10px;">
        <p><strong>ID:</strong> {{ $task->id }}</p>
        <p><strong>Title:</strong> {{ $task->title }}</p>
        <p><strong>Description:</strong> {{ $task->description ?: 'No description' }}</p>
        <p><strong>Difficulty:</strong> {{ $task->difficulty }}</p>
        <p><strong>Priority:</strong> {{ $task->priority }}</p>

        <p>
            <strong>Deadline:</strong>
            {{ $task->deadline ? $task->deadline->format('Y-m-d H:i') : 'No deadline' }}
        </p>

        <p><strong>Status:</strong> {{ $task->done ? 'Done' : 'Not done' }}</p>
        <p><strong>Streaks:</strong> {{ $task->streaks ?? 0 }}</p>

        <p>
            <strong>Frequency:</strong>
            @if (!empty($task->frequency) && is_array($task->frequency))
                {{ implode(', ', $task->frequency) }}
            @else
                No frequency set
            @endif
        </p>

        <p><strong>Category:</strong> {{ $task->category?->title ?? 'No category' }}</p>
        <p><strong>User ID:</strong> {{ $task->user_id }}</p>
        <p><strong>Created At:</strong> {{ $task->created_at?->format('Y-m-d H:i') }}</p>
        <p><strong>Updated At:</strong> {{ $task->updated_at?->format('Y-m-d H:i') }}</p>
    </div>
</div>
<p>--------------------------------------------------</p>
@empty

<p>no tasks created yet</p>

@endforelse

@endsection --}}


@push('scripts')
    <script>
        let check = document.querySelectorAll(".task_done");
        check.forEach(c => {
            c.addEventListener('change', function() {
                this.form.submit();
            })
        });
    </script>
@endpush
