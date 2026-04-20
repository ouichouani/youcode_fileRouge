@extends('components.layout')

@section('title')
    DASHBOARD
@endsection

@section('nav')
    <nav>
        <a href='{{ route('dashboard') }}'>board</a>
        <a href='{{ route('habits.index') }}'>habits</a>
        <a href='{{ route('tasks.index') }}'>tasks</a>
        <a href='{{ route('logs.index') }}'>historie</a>
        <a href='{{ route('categories.index') }}'>categories</a>
    </nav>
@endsection

@section('content')
    <p>--------------------------- table ---------------------------</p>

    <table border=1>
        <thead>
            <tr>
                <td>habit</td>
                @for ($i = 1; $i <= now()->daysInMonth; $i++)
                    <td>{{ $i }}</td>
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
                    <td>{{ $h->title }}</td>
                    @for ($i = 1; $i <= now()->daysInMonth; $i++)
                        @if (now()->day > $i)
                            @if (isset($logs[$current_log_index]) && $logs[$current_log_index]->completed_date->day == $i)
                                @php
                                    $current_log_index++;
                                @endphp
                                <td style="background-color: green">
                                    <p>ok</p>
                                </td>
                            @else
                                <td style="background-color: red">

                                    <p>no</p>
                                </td>
                            @endif
                        @elseif (now()->day == $i)
                            <td>
                                @php
                                    //check if the current day log is created or not
                                    $lastLog = $logs->last();
                                @endphp

                                @if ($lastLog?->completed_date->day != $i)
                                    <form action="{{ route('logs.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="task_id" value="{{ $h->id }}">
                                        <input type="submit" value="create">
                                    </form>
                                @else
                                    <form action="{{ route('logs.destroy', $lastLog->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="task_id" value="{{ $h->id }}">
                                        <input type="submit" value="delete">
                                    </form>
                                @endif
                            </td>
                        @else
                            <td>.</td>
                        @endif
                    @endfor
                </tr>
            @endforeach
        </tbody>
    </table>

@endsection
