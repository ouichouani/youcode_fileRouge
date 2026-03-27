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
