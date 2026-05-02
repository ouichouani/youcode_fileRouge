@extends('components.layout')

@section('title')
    HISTORY
@endsection

@section('nav')
    <a href='{{ route('dashboard') }}'>board</a>
    <a href='{{ route('habits.index') }}'>habits</a>
    <a href='{{ route('tasks.index') }}'>tasks</a>
    <a href='{{ route('categories.index') }}'>categories</a>
    <a href='{{ route('logs.index') }}'>historie</a>
@endsection

@section('content')
    <section class="space-y-8">
        @if ($habits->isEmpty())
            <div class="border border-solid border-white/30 rounded-4xl bg-[#151b23] px-6 py-10 text-center text-white/70">
                No habits yet.
            </div>
        @else
            <div
                class="rounded-2xl border border-white/30 bg-[#151b23] px-6 py-10 text-center text-white md:hidden portrait:block landscape:hidden">
                <h2 class="text-lg font-semibold">Rotate your phone</h2>
                <p class="mt-2 text-sm text-white/70">Turn your phone sideways to view the habit history board.</p>
            </div>

            @foreach ($months as $month)
                @php
                    $monthStart = $month->copy()->startOfMonth();
                    $monthEnd = $month->copy()->endOfMonth();
                    $daysInMonth = $month->daysInMonth;
                @endphp

                <section class="hidden space-y-3 md:block landscape:block">
                    <div class="px-2">
                        <h2 class="text-xl font-semibold text-white">{{ $month->format('F Y') }}</h2>
                        <p class="text-sm text-white/60">
                            Habits history since {{ $oldestHabit->created_at->format('M d, Y') }}
                        </p>
                    </div>

                    <div class="overflow-x-auto">
                        <div class="flex justify-center items-center min-w-max">

                            <table class="border border-solid border-white/30 rounded-4xl">

                                <thead>
                                    <tr>
                                        <td
                                            class="px-3 border-b border-solid border-white/30 min-w-[25px] bg-[#151b23] sticky left-0 block">
                                            habit
                                        </td>

                                        @for ($i = 1; $i <= $daysInMonth; $i++)
                                            <td
                                                class="border border-solid border-white/30 min-w-[25px] text-center bg-[#151b23]">
                                                {{ $i }}
                                            </td>
                                        @endfor
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($habits as $h)
                                        @php
                                            $logs = $h->logs;
                                            $current_log_index = 0;
                                        @endphp

                                        <tr>
                                            <td
                                                class="px-3 border-b border-solid border-white/30 min-w-[25px]  bg-[#151b23] sticky left-0 block">
                                                <a href="{{ route('habits.show', $h->id) }}">{{ $h->title }}</a>
                                            </td>


                                            @for ($i = 1; $i <= $daysInMonth; $i++)
                                                @php
                                                    $now = now();
                                                    $index_date = new DateTime("$i-$month->month-$month->year");
                                                    $index_date = \Carbon\Carbon::parse($index_date);

                                                    $day = $index_date->format('l');
                                                    $lastLog = $logs->last();

                                                @endphp

                                                @if (now()->month > $month->month && in_array($day, $h->frequency) && $h->created_at->toDateString() <= $index_date->toDateString())
                                                    @if (isset($logs[$current_log_index]) && $logs[$current_log_index]->completed_date->day == $i)
                                                        @php
                                                            $current_log_index++;
                                                        @endphp
                                                        <td
                                                            class="border border-solid border-white/50 min-w-[25px] text-center bg-green-800">
                                                            <img src="{{ asset('svg/ok.svg') }}" class="w-[15px] m-auto"
                                                                alt="">
                                                        </td>
                                                    @else
                                                        <td
                                                            class="border border-solid border-white/50 min-w-[25px] text-center bg-red-800">
                                                            <img src="{{ asset('svg/x.svg') }}" class="w-[15px] m-auto"
                                                                alt="">
                                                        </td>
                                                    @endif
                                                @elseif (now()->day > $i && in_array($day, $h->frequency) && $h->created_at->toDateString() <= $index_date->toDateString())
                                                    @if (isset($logs[$current_log_index]) && $logs[$current_log_index]->completed_date->day == $i)
                                                        @php
                                                            $current_log_index++;
                                                        @endphp
                                                        <td
                                                            class="border border-solid border-white/50 min-w-[25px] text-center bg-green-800">
                                                            <img src="{{ asset('svg/ok.svg') }}" class="w-[15px] m-auto"
                                                                alt="">
                                                        </td>
                                                    @else
                                                        <td
                                                            class="border border-solid border-white/50 min-w-[25px] text-center bg-red-800">
                                                            <img src="{{ asset('svg/x.svg') }}" class="w-[15px] m-auto"
                                                                alt="">
                                                        </td>
                                                    @endif
                                                @elseif (now()->day == $i && in_array($day, $h?->frequency) && $h->created_at->toDateString() <= $index_date->toDateString())
                                                    @if ($lastLog?->completed_date->day != $i)
                                                        <td
                                                            class="border border-solid border-white/50 min-w-[25px] text-center bg-red-800">
                                                            <img src="{{ asset('svg/x.svg') }}" class="w-[15px] m-auto"
                                                                alt="">
                                                        </td>
                                                    @else
                                                        <td
                                                            class="border border-solid border-white/50 min-w-[25px] text-center bg-green-800">
                                                            <img src="{{ asset('svg/ok.svg') }}" class="w-[15px] m-auto"
                                                                alt="">
                                                        </td>
                                                    @endif
                                                @else
                                                    <td
                                                        class="border border-solid border-white/50 min-w-[25px] text-center">
                                                    </td>
                                                @endif
                                            @endfor
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>

                    </div>
                </section>
            @endforeach
        @endif
    </section>
@endsection
