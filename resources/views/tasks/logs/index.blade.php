@extends('components.layout')

@section('title')
    HISTORY
@endsection

@section('nav')
    <a href='{{ route('dashboard') }}'>board</a>
    <a href='{{ route('habits.index') }}'>habits</a>
    <a href='{{ route('tasks.index') }}'>tasks</a>
    <a href='{{ route('logs.index') }}'>historie</a>
    <a href='{{ route('categories.index') }}'>categories</a>
@endsection

@section('content')
    <section class="space-y-8">
        @if ($habits->isEmpty())
            <div class="border border-solid border-white/30 rounded-4xl bg-[#151b23] px-6 py-10 text-center text-white/70">
                No habits yet.
            </div>
        @else
            @foreach ($months as $month)
                @php
                    $monthStart = $month->copy()->startOfMonth();
                    $monthEnd = $month->copy()->endOfMonth();
                    $daysInMonth = $month->daysInMonth;
                @endphp

                <section class="space-y-3">
                    <div class="px-2">
                        <h2 class="text-xl font-semibold text-white">{{ $month->format('F Y') }}</h2>
                        <p class="text-sm text-white/60">
                            Habits history since {{ $oldestHabit->created_at->format('M d, Y') }}
                        </p>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="border border-solid border-white/30 rounded-4xl min-w-full">
                            <thead>
                                <tr>
                                    <td
                                        class="px-3 border-b border-solid border-white/30 min-w-[180px] bg-[#151b23] sticky left-0">
                                        habit
                                    </td>
                                    @for ($day = 1; $day <= $daysInMonth; $day++)
                                        <td
                                            class="border border-solid border-white/30 min-w-[25px] text-center bg-[#151b23]">
                                            {{ $day }}
                                        </td>
                                    @endfor
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($habits as $habit)
                                    @php
                                        $logsByDate = $habit->logs
                                            ->filter(
                                                fn($log) => $log->completed_date->betweenIncluded($monthStart, $monthEnd),
                                            )
                                            ->keyBy(fn($log) => $log->completed_date->toDateString());
                                    @endphp

                                    <tr>
                                        <td
                                            class="px-3 border-b border-solid border-white/30 min-w-[180px] bg-[#0f141b] sticky left-0">
                                            {{ $habit->title }}
                                        </td>

                                        @for ($day = 1; $day <= $daysInMonth; $day++)
                                            @php
                                                $date = $month->copy()->day($day);
                                                $dateKey = $date->toDateString();
                                                $isScheduled = in_array($date->format('l'), $habit->frequency ?? []);
                                                $isAfterCreation = $date->greaterThanOrEqualTo(
                                                    $habit->created_at->copy()->startOfDay(),
                                                );
                                                $log = $logsByDate->get($dateKey);
                                            @endphp

                                            @if (!$isAfterCreation || !$isScheduled)
                                                <td
                                                    class="border border-solid border-white/50 min-w-[25px] text-center bg-transparent">
                                                </td>
                                            @elseif ($log)
                                                <td
                                                    class="border border-solid border-white/50 min-w-[25px] text-center bg-green-800">
                                                    <img src="{{ asset('svg/ok.svg') }}" class="w-[15px] m-auto" alt="">
                                                </td>
                                            @elseif ($date->isFuture())
                                                <td
                                                    class="border border-solid border-white/50 min-w-[25px] text-center bg-transparent">
                                                </td>
                                            @else
                                                <td
                                                    class="border border-solid border-white/50 min-w-[25px] text-center bg-red-800">
                                                    <img src="{{ asset('svg/x.svg') }}" class="w-[15px] m-auto" alt="">
                                                </td>
                                            @endif
                                        @endfor
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </section>
            @endforeach
        @endif
    </section>
@endsection
