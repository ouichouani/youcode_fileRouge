@extends('components.layout')

@section('title')
    SHOW CATEGORY
@endsection

@section('content')

<section style="background-color: {{ $category->color }}">
    <p>{{ $category->title }}</p>
    <br>
    <p>{{ $category->description }}</p>
    <br>

    <a href="{{ route('categories.edit' ,$category->id ) }}">update</a>
    <form action="{{ route('categories.destroy' , $category->id) }}" method="POST">
        @csrf
        @method('DELETE')
        <button>delete</button>
    </form>
</section>



@forelse ($tasks as $task)
    <p>{{$task->title}}</p>
    <br>
    <p>{{$task->description}}</p>
    <br>
    <p>-----------------------</p>
@empty
<p>this cat doesn't have any tasks</p>

@endforelse


@forelse ($habits as $habit)
    <p>{{$habit->title}}</p>
    <br>
    <p>{{$habit->description}}</p>
    <br>
    <p>-----------------------</p>
@empty
<p>this cat doesn't have any habits</p>

@endforelse

@endsection