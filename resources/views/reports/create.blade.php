@extends('components.layout')

@section('title')
    CREATE REPORT
@endsection

@section('content')

<form action="{{ route("reports.store") }}" method="POST">
    @csrf
    <input type="text" name="description" id="">
    <select name="type" id="">
        <option value='spam' >spam</option>
        <option value='harassment' >harassment</option>
        <option value='hate_speech' >hate_speech</option>
        <option value='violence' >violence</option>
        <option value='nudity' >nudity</option>
        <option value='misinformation' >misinformation</option>
        <option value='copyright' >copyright</option>
        <option value='scam' >scam</option>
        <option value='other' >other</option>
    </select>
    <input type="hidden" name="post_id" value='{{ $post_id }}'>
    <button>report</button>
</form>

@endsection