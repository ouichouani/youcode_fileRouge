@extends('components.layout')

@section('title')
    HABITS
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
                    href="{{ route('habits.create') }}">add habit</a>
            </div>
            @foreach ($categories as $cat)
                <div class='border border-white/30 border-solid rounded-lg flex flex-col gap-1  '>

                    <div class="bg-[{{ $cat->color }}]/10 w-full p-[15px] border-b border-solid border-white/30">
                        <a href="{{ route('categories.show', $cat->id) }}" class="flex gap-3 items-center w-fit">
                            <div
                                class='bg-[{{ $cat->color }}] border border-white/30 border-solid rounded-full w-[20px] h-[20px]'>
                            </div>
                            <h1 class="text-3xl font-bold">{{ $cat->title }}</h1>
                        </a>
                    </div>

                    <section class="flex flex-col gap-4 p-[15px]">
                        @forelse ($cat->habits as $habit)
                            <div class="flex flex-col gap-3">
                                <a href='{{ route('habits.show', $habit->id) }}' class='flex items-center gap-2'>
                                    <p class="font-bold text-md">- {{ $habit->title }}</p>
                                </a>
                            </div>
                        @empty
                            <p>no habits for this cat</p>
                        @endforelse
                    </section>

                </div>
                <br>
            @endforeach


            @if (isset($abandoned_habits) && count($abandoned_habits))
                <div class='border border-white/30 border-solid rounded-lg flex flex-col gap-1  '>
                    
                    <div class="bg-[#25171c] w-full p-[15px] border-b border-solid border-white/30 rounded-t-lg">
                        <div class="flex gap-3 items-center w-fit">
                            <div class='bg-[#25171c]/50 border border-white/30 border-solid rounded-full w-[20px] h-[20px]'>
                            </div>
                            <h1 class="text-3xl font-bold">abandoned habits</h1>
                        </div>
                    </div>

                    <section class="flex flex-col gap-4 p-[15px]">
                        @forelse ($abandoned_habits as $habit)
                            <div class="flex flex-col gap-3">
                                <a href='{{ route('habits.show', $habit->id) }}' class='flex items-center gap-2'>
                                    <p class="font-bold text-md">- {{ $habit->title }}</p>
                                </a>
                            </div>
                        @empty
                            <p>no abandoned habits</p>
                        @endforelse
                    </section>

                </div>
                <br>
            @endif

        </div>
    </section>
@endsection
