@extends('components.layout')

@section('title')
    HISTORY
@endsection


@section('content')

@forelse ($logs as $l )
    <p> - {{$l->created_at}}</p>
@empty
    <p>no logs yet</p>
@endforelse

@endsection