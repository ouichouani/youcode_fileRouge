@extends('components.layout')

@section('title')
    HABITS
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
        // dd($categories) ;
        // $categories_indexes = array_unique($habits->pluck('category_id')->toArray());
        // $categories = $habits->pluck('category');
        // dd($categories[0]);
    @endphp


    @foreach ($categories as $cat)
        <div
            class='border border-white/30 border-solid rounded-lg p-[20px] flex flex-col gap-15 bg-[{{ $cat->color}}]/10 '>

            <a href="{{ route('categories.show', $cat->id) }}" class="flex gap-3 items-center">
                <div class='bg-[{{ $cat->color }}] rounded-full w-[20px] h-[20px]'></div>
                <h1 class="text-3xl font-bold">{{ $cat->title }}</h1>
            </a>

            <section class="flex flex-col gap-7">
                @forelse ($cat->habits as $habit)
                    <div class="flex flex-col gap-3">
                        <a href='{{ route('habits.show', $habit->id) }}' class='flex items-center gap-2'>
                            <p class="font-bold text-lg">{{ $habit->title }}</p>
                        </a>
                        <p class="text-[#9198a1] w-[80%] pl-[10px]">&emsp; frequency : {{ implode(' ... ' ,$habit->frequency) }} </p>
                        <p class="text-[#9198a1] w-[80%] pl-[10px]">&emsp; priority : {{ $habit->priority }} </p>
                        <p class="text-[#9198a1] w-[80%] pl-[10px]">&emsp; difficulty : {{ $habit->difficulty }} </p>
                        <p class="text-[#9198a1] w-[80%] pl-[10px]">&emsp; streaks : {{ $habit->streaks }} </p>
                        <p class="text-[#9198a1] w-[80%] pl-[10px]">&emsp; description : {{ $habit->description }} </p>
                    </div>
                @empty
                    <p>no habits for this cat</p>
                @endforelse
            </section>

        </div>
        <br>
    @endforeach


    {{-- 
@forelse ($habits as $habit )
<div style="max-width: 700px; margin: 30px auto; font-family: Arial, sans-serif;">
    <h1>habit Details</h1>

    <div style="padding: 20px; border: 1px solid #ddd; border-radius: 10px;">
        <p><strong>ID:</strong> {{ $habit->id }}</p>
        <p><strong>Title:</strong> {{ $habit->title }}</p>
        <p><strong>Description:</strong> {{ $habit->description ?: 'No description' }}</p>
        <p><strong>Difficulty:</strong> {{ $habit->difficulty }}</p>
        <p><strong>Priority:</strong> {{ $habit->priority }}</p>

        <p>
            <strong>Deadline:</strong>
            {{ $habit->deadline ? $habit->deadline->format('Y-m-d H:i') : 'No deadline' }}
        </p>

        <p><strong>Status:</strong> {{ $habit->done ? 'Done' : 'Not done' }}</p>
        <p><strong>Streaks:</strong> {{ $habit->streaks ?? 0 }}</p>

        <p>
            <strong>Frequency:</strong>
            @if (!empty($habit->frequency) && is_array($habit->frequency))
                {{ implode(', ', $habit->frequency) }}
            @else
                No frequency set
            @endif
        </p>

        <p><strong>Category:</strong> {{ $habit->category?->title ?? 'No category' }}</p>
        <p><strong>User ID:</strong> {{ $habit->user_id }}</p>
        <p><strong>Created At:</strong> {{ $habit->created_at?->format('Y-m-d H:i') }}</p>
        <p><strong>Updated At:</strong> {{ $habit->updated_at?->format('Y-m-d H:i') }}</p>
    </div>
</div>
<p>--------------------------------------------------</p>
@empty

<p>no habits created yet</p>

@endforelse
 --}}
@endsection
