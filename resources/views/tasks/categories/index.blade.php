@extends('components.layout')

@section('title')
    @if (auth()->user()->role == 'Admin' || auth()->user()->role == 'Moderator')
        CONTROLL PANEL
    @else
        CATEGORIES
    @endif
@endsection

@if (request()->routeIs(['categories.global']))
    @if (auth()->user()->role == 'Admin' || auth()->user()->role == 'Moderator')
        @section('nav')
        <a href="{{ route('blackList') }}">black list</a>
        <a href="{{ route('users.index') }}">active users</a>
        <a href="{{ route('posts.hidden') }}">posts</a>
        <a href="{{ route('reports.index') }}">reports</a>
        @can('ban', App\Models\User::class)
        <a href="{{ route('categories.global') }}">categories</a>
        @endcan
        @endsection
    @endif
@else
    @section('nav')
        <a href='{{ route('dashboard') }}'>board</a>
        <a href='{{ route('habits.index') }}'>habits</a>
        <a href='{{ route('tasks.index') }}'>tasks</a>
        <a href='{{ route('categories.index') }}'>categories</a>
        <a href='{{ route('logs.index') }}'>historie</a>
    @endsection
@endif



@section('content')
    <section class="mx-auto w-full max-w-6xl px-4 py-6">
        <div class="relative w-full pt-15">

            <div class="flex gap-3 absolute top-[0px] right-[0px]">
                <a class="p-2 bg-[#151b23] border border-solid border-white/30 rounded-lg transition hover:border-white/60"
                    href="{{ route('categories.create') }}">add category</a>
            </div>

            <section class="flex flex-col gap-3">
                @if (!request()->routeIs(['categories.global']))
                    <section class='border border-white/30 border-solid rounded-lg p-[20px] flex flex-col gap-15 '>
                        <div class="flex flex-col gap-5">
                            <h2 class="text-3xl font-bold">Private categories</h2>
                            <p class="text-[#9198a1] pl-[10px] w-[80%]">this section is visible only tho the you , no one
                                can
                                see
                                your categories and no one can modify them . </p>
                        </div>

                        <div class="flex flex-col gap-7">
                            @forelse ($categories as $c)
                                @if (!$c->is_global)
                                    <div class="flex flex-col gap-3">
                                        <a href='{{ route('categories.show', $c->id) }}' class='flex items-center gap-2'>
                                            <div
                                                class="w-[12px] h-[12px] bg-[{{ $c->color }}] rounded-full border border-solid border-white">
                                            </div>
                                            <p>{{ $c->title }}</p>
                                        </a>
                                        <p class="text-[#9198a1] w-[80%] pl-[10px]">&emsp;{{ $c->description }} </p>
                                    </div>
                                @endif
                            @empty
                                <p>no categories created yet</p>
                            @endforelse
                        </div>
                    </section>
                @endif


                @can('accessGlobalCategories', App\Models\Category::class)
                    <section class='border border-white/30 border-solid rounded-lg p-[20px] flex flex-col gap-15 bg-[#25171c] '>
                        <div class="flex flex-col gap-5">
                            <h2 class="text-3xl font-bold">Global categories</h2>
                            <p class="text-[#9198a1] pl-[10px] w-[80%]">this section is visible only to admin , global
                                categories
                                are shared with all
                                users , so updating it or removing a
                                category will affect all users </p>
                        </div>

                        <div class="flex flex-col gap-7">
                            @forelse ($categories as $c)
                                @if ($c->is_global)
                                    <div class="flex flex-col gap-3">
                                        <a href='{{ route('categories.show', $c->id) }}' class='flex items-center gap-2'>
                                            <div
                                                class="w-[12px] h-[12px] bg-[{{ $c->color }}] rounded-full border border-solid border-white">
                                            </div>
                                            <p>{{ $c->title }}</p>
                                        </a>
                                        <p class="text-[#9198a1] w-[80%] pl-[10px]">&emsp;{{ $c->description }} </p>
                                    </div>
                                @endif
                            @empty
                                <p>no global cat created yet</p>
                            @endforelse
                        </div>
                    </section>
                @endcan

            </section>
        </div>
    </section>
@endsection
