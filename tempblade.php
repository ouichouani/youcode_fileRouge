<?php

// @can('ShowGlobalCategory', App\Models\Category::class)
//     <h1>Global categories</h1>
//     @foreach ($global_categories as $cat)
//         <p>{{ $cat->title }}</p>
//     @endforeach
// @endcan 


// <br>
// <h2>tasks</h2>
// @forelse ($tasks as $task)
//     <p>{{ $task?->title }}</p>
//     <p>- {{ $task?->category?->title }}</p>
//     <p>-----------------------------</p>
// @empty
//     <p>there is notasks created yet</p>
// @endforelse

// <br>
// <h2>habits</h2>
// @forelse ($habits as $habit)
//     <p>{{ $habit?->title }}</p>
// @empty
//     <p>there is no habits created yet</p>
// @endforelse

// <p>--------------------------- logs ---------------------------</p>

// @foreach ($habits as $h)
//     @forelse ($h->logs as $log)
//         <p> - {{ $log->completed_date }}</p>
//     @empty
//         <p>no progress</p>
//     @endforelse
//     <p>--------------</p>
// @endforeach

