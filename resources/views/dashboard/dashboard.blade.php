@extends('components.layout')

@section('title')
    DASHBOARD
@endsection

@section('nav')
    <a href='{{ route('dashboard') }}'>board</a>
    <a href='{{ route('habits.index') }}'>habits</a>
    <a href='{{ route('tasks.index') }}'>tasks</a>
    <a href='{{ route('logs.index') }}'>historie</a>
    <a href='{{ route('categories.index') }}'>categories</a>
@endsection

@section('content')
    <table class="border border-solid border-white/30 m-5 rounded-4xl">
        <thead>
            <tr>
                <td class="px-3 border-b border-solid border-white/30 min-w-[25px]">habit</td>
                @for ($i = 1; $i <= now()->daysInMonth; $i++)
                    <td class="border border-solid border-white min-w-[25px] text-center">{{ $i }}</td>
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
                    <td class="px-3 border-b border-solid border-white/20 min-w-[25px]">{{ $h->title }}</td>
                    @for ($i = 1; $i <= now()->daysInMonth; $i++)
                    @php
                        $now = now() ;
                        $index_date = new DateTime ("$i-$now->month-$now->year") ;
                        $day = $index_date->format('l') ;
                    @endphp
                        @if (now()->day > $i && in_array($day , $h->frequency))
                            @if (isset($logs[$current_log_index]) && $logs[$current_log_index]->completed_date->day == $i)
                                @php
                                    $current_log_index++;
                                @endphp
                                <td class="border border-solid border-white/80 min-w-[25px] text-center bg-green-800"><img
                                        src="{{ asset('ok.svg') }}" class="w-[15px] m-auto" alt=""></td>
                            @else
                                <td class="border border-solid border-white/80 min-w-[25px] text-center bg-red-800"><img
                                        src="{{ asset('x.svg') }}" class="w-[15px] m-auto" alt=""></td>
                            @endif

                            @php
                                //check if the current day log is created or not
                                $lastLog = $logs->last();
                                // $currentDay = now()->format('l');
                            @endphp
                        @elseif (now()->day == $i && in_array(now()->format('l'), $h?->frequency))
                            @if ($lastLog?->completed_date->day != $i)
                                <td class="border border-solid border-white/80 min-w-[25px] text-center">
                                    <form action="{{ route('logs.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="task_id" value="{{ $h->id }}">
                                        <button><img src="{{ asset('ok.svg') }}" class="w-[15px] m-auto"
                                                alt=""></button>
                                    </form>
                                </td>
                            @else
                                <td class="border border-solid border-white/80 min-w-[25px] text-center bg-green-800">
                                    <form action="{{ route('logs.destroy', $lastLog->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="task_id" value="{{ $h->id }}">
                                        <button><img src="{{ asset('ok.svg') }}" class="w-[15px] m-auto"
                                                alt=""></button>
                                    </form>
                                </td>
                            @endif
                        @else
                            <td class="border border-solid border-white/80 min-w-[25px] text-center"></td>
                        @endif
                    @endfor
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
