@extends('components.layout')


@section('title')
    TASKS
@endsection

@section('nav')
    <a href='{{ route('dashboard') }}'>board</a>
    <a href='{{ route('habits.index') }}'>habits</a>
    <a href='{{ route('tasks.index') }}'>tasks</a>
    <a href='{{ route('categories.index') }}'>categories</a>
    <a href='{{ route('logs.index') }}'>historie</a>
@endsection



@section('content')
    <section class="mx-auto w-full max-w-6xl px-4 py-6">
        <div class="relative w-full pt-15">

            <div class="flex gap-3 absolute top-[0px] right-[0px]">
                <a class="p-2 bg-[#151b23] border border-solid border-white/30 rounded-lg transition hover:border-white/60"
                    href="{{ route('tasks.create') }}">add task</a>
            </div>

            @foreach ($categories as $cat)
                <div class='border border-white/30 border-solid rounded-lg flex flex-col gap-15  '>

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
                                    <input type="checkbox" class="task_done cursor-pointer"
                                        {{ $task->done ? 'checked' : '' }}>
                                    <a href='{{ route('tasks.show', $task->id) }}' class='flex items-center gap-2'>
                                        <p class="font-bold text-lg {{ $task->done ? 'line-through' : '' }}">
                                            {{ $task->title }}
                                        </p>
                                    </a>
                                </form>
                                <div class="flex">
                                    <span class="text-[#9198a1] pl-[10px]">&emsp; priority : {{ $task->priority }} </span>
                                    <span class="text-[#9198a1] pl-[10px]">&emsp; difficulty : {{ $task->difficulty }}
                                    </span>
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

            @if (isset($abandoned_tasks) && count($abandoned_tasks))
                <div class='border border-white/30 border-solid rounded-lg flex flex-col gap-15  '>
                    <div class="bg-[#25171c] w-full p-[20px] border-b border-solid border-white/30 rounded-t-lg">
                        <div class="flex gap-3 items-center w-fit">
                            <div class='bg-[#25171c]/50 border border-white/30 border-solid rounded-full w-[20px] h-[20px]'>
                            </div>
                            <h1 class="text-3xl font-bold">abandoned tasks</h1>
                        </div>
                    </div>

                    <section class="flex flex-col gap-7 p-[20px]">
                        @forelse ($abandoned_tasks as $task)
                            <div class="flex flex-col gap-3">

                                <form class="flex gap-3" action="{{ route('tasks.done', $task->id) }}" method='POST'>
                                    @csrf
                                    <input type="checkbox" class="task_done cursor-pointer"
                                        {{ $task->done ? 'checked' : '' }}>
                                    <a href='{{ route('tasks.show', $task->id) }}' class='flex items-center gap-2'>
                                        <p class="font-bold text-lg {{ $task->done ? 'line-through' : '' }}">
                                            {{ $task->title }}
                                        </p>
                                    </a>
                                </form>

                                <div class="flex">
                                    <span class="text-[#9198a1] pl-[10px]">&emsp; priority : {{ $task->priority }} </span>
                                    <span class="text-[#9198a1] pl-[10px]">&emsp; difficulty : {{ $task->difficulty }}
                                    </span>
                                    <span class="text-[#9198a1] pl-[10px]" title='deadline'>&emsp; {{ $task->deadline }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <p>no abandoned tasks</p>
                        @endforelse
                    </section>

                </div>
                <br>
            @endif
        </div>
    </section>
@endsection

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
