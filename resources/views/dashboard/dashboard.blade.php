<pre>{{ $user }}</pre>
<pre>{{ $user->habits }}</pre>
{{-- <pre>{{/$user->habits['category']}}</pre> --}}

<h2>tasks</h2>

{{-- {{dd($tasks[1]->category->title)}} --}}
@forelse ($tasks as $task)
    <p>{{ $task?->title }}</p>
    <p>- {{ $task?->category?->title }}</p>
    <p>-----------------------------</p>


@empty
    <p>there is notasks created yet</p>
@endforelse

<h2>habits</h2>

@forelse ($habits as $habit)
    <p>{{ $habit?->title }}</p>
    <p>{{ $habit?->category?->title }}</p>
    <p>-----------------------------</p>

@empty
    <p>there is no habits created yet</p>
@endforelse

<p>--------------------------- tasks ---------------------------</p>

{{ today() }}

<p>--------------------------- logs ---------------------------</p>

@foreach ($habits as $h)
    @forelse ($h->logs as $log)
        <p> - {{ $log->completed_date }}</p>
    @empty
        <p>no progress</p>
    @endforelse
    <p>--------------</p>
@endforeach


<p>--------------------------- logs ---------------------------</p>

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
                    @if (now()->day == $i)
                        <td>
                            @php
                                //check if the current day log is created or not 
                                $lastLog = $logs->last() ;
                            @endphp

                            @if ($lastLog?->completed_date->day != $i)
                                <form action="{{ route('logs.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="task_id" value="{{ $h->id }}">
                                    <input type="submit" value="create">                                    
                                </form>

                                @else

                                <form action="{{ route('logs.destroy' , $lastLog->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="task_id" value="{{ $h->id }}">
                                    <input type="submit" value="delete">                                    
                                </form>
                            @endif
                        </td>
                    @elseif (now()->day > $i)
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
                    @endif
                @endfor
            </tr>
        @endforeach
    </tbody>
</table>
