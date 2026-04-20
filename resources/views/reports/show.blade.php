@extends('components.layout')

@section('title')
    DASHBOARD
@endsection

@section('content')

<p> ------------- post ------------</p>
<p>{{ $post->id }}</p>
<p>{{ $post->content }}</p>
<p>- comments</p>
@forelse ($comments as $c )
    <pre>   - {{ $c->content }}</pre>
@empty
    <pre>   - no comments</pre>
@endforelse

<p> ------------- reports ------------</p>
<p>{{ $user->email}} make a report : </p>
<p>id : {{ $report->id }}</p>
<p>content : {{ $report->description }}</p>
@endsection