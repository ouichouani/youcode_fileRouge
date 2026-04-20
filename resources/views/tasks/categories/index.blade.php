@extends('components.layout')

@section('title')
    CATEGORIES
@endsection


@section('content')


@can('accessGlobalCategories', App\Models\Category::class)
    <a href="{{ route('categories.create') }}">create a global category</a>
@endcan
@forelse ($categories as $c)
    <p>{{ $c->title }}</p>
    @can('accessGlobalCategories', $c)
        <a href="{{ route('categories.edit', $c->id) }}">update for "{{ $c->title }}"</a>
        <form action="{{ route('categories.destroy' , $c->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type='submit'>delete</button>
        </form>
    @endcan
@empty
    <p>no cat created yet</p>
@endforelse


@endsection
