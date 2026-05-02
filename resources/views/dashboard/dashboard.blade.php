@extends('components.layout')

@section('title')
    DASHBOARD
@endsection

@section('nav')
    <a href='{{ route('dashboard') }}'>board</a>
    <a href='{{ route('habits.index') }}'>habits</a>
    <a href='{{ route('tasks.index') }}'>tasks</a>
    <a href='{{ route('categories.index') }}'>categories</a>
    <a href='{{ route('logs.index') }}'>historie</a>
@endsection

@section('content')
    <section class="space-y-4">
        <div
            class="rounded-2xl border border-white/30 bg-[#151b23] px-6 py-10 text-center text-white md:hidden portrait:block landscape:hidden">
            <h2 class="text-lg font-semibold">Rotate your phone</h2>
            <p class="mt-2 text-sm text-white/70">Turn your phone sideways to view the habit board.</p>
        </div>

        <div class="hidden md:block landscape:block">
            <div class="overflow-x-auto">
                <div class="flex justify-center items-center min-w-max">
                    <table class="border border-solid border-white/30 rounded-4xl">
                        <thead>
                            <tr>
                                <td class="px-3 border-b border-solid border-white/30 min-w-[25px] bg-[#151b23] sticky left-0 block">habit</td>
                                @for ($i = 1; $i <= now()->daysInMonth; $i++)
                                    <td class="border border-solid border-white/30 min-w-[25px] text-center bg-[#151b23]">
                                        {{ $i }}</td>
                                @endfor
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($habits as $h)
                                @php
                                    $logs = $h->logs;
                                    $lastLog = $logs->last();
                                    $current_log_index = 0;
                                @endphp
                                <tr>
                                    <td class="px-3 border border-solid border-white/30 min-w-[25px] bg-[#151b23] sticky left-0 block"> <a href="{{ route('habits.show' , $h->id) }}">{{ $h->title }}</a></td>

                                    @for ($i = 1; $i <= now()->daysInMonth; $i++)
                                        @php
                                            $now = now();
                                            $index_date = new DateTime("$i-$now->month-$now->year");
                                            $index_date = \Carbon\Carbon::parse($index_date);
                                            $day = $index_date->format('l');

                                        @endphp
                                        @if (now()->day > $i && in_array($day, $h->frequency) && $h->created_at->toDateString() <= $index_date->toDateString())
                                            @if (isset($logs[$current_log_index]) && $logs[$current_log_index]->completed_date->day == $i)
                                                @php
                                                    $current_log_index++;
                                                @endphp
                                                <td class="border border-solid border-white/50 min-w-[25px] text-center bg-green-800">
                                                    <img src="{{ asset('svg/ok.svg') }}" class="w-[15px] m-auto" alt=""></td>
                                            @else
                                                <td class="border border-solid border-white/50 min-w-[25px] text-center bg-red-800"><img
                                                        src="{{ asset('svg/x.svg') }}" class="w-[15px] m-auto" alt=""></td>
                                            @endif

                                        @elseif ( now()->day == $i && in_array(now()->format('l'), $h?->frequency))


                                            @if ($lastLog?->completed_date->day != $i)
                                                <td class="border border-solid border-white/50 min-w-[25px] text-center cursor-pointer">
                                                    <form action="{{ route('logs.store') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="task_id" value="{{ $h->id }}">
                                                        <button class="cursor-pointer">
                                                            <img src="{{ asset('svg/ok.svg') }}" class="w-[15px] pointer-events-none"
                                                                alt="">
                                                        </button>
                                                    </form>
                                                </td>
                                            @else
                                                <td
                                                    class="border border-solid border-white/50 min-w-[25px] text-center bg-green-800 cursor-pointer">
                                                    <form action="{{ route('logs.destroy', $lastLog->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <input type="hidden" name="task_id" value="{{ $h->id }}">
                                                        <button class="cursor-pointer">
                                                            <img src="{{ asset('svg/ok.svg') }}"
                                                                class="w-[15px] m-auto pointer-events-none" alt="">
                                                        </button>
                                                    </form>
                                                </td>
                                            @endif
                                        @else
                                            <td class="border border-solid border-white/50 min-w-[25px] text-center"></td>
                                        @endif
                                    @endfor
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection
