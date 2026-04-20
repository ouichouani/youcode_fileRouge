@extends('components.layout')

@section('title')
    EDIT CTEGORY
@endsection


@section('content')

<form action="{{ route('categories.update' , $category->id) }}" method="POST">
    @csrf
    @method('PUT')
    <input type="text" name='title' placeholder='title' value="{{ $category->title }}" >
    @error('title')
        <div style="color:red;">{{ $message }}</div>
    @enderror
    <br>
    <input type="color" name="color" id="" value="{{ $category->color }}">
    @error('color')
        <div style="color:red;">{{ $message }}</div>
    @enderror
    <br>
    <textarea name="description" placeholder='description' id="" cols="30" rows="10">{{ $category->description }}</textarea>
    @error('description')
        <div style="color:red;">{{ $message }}</div>
    @enderror
    <br>
    <button>update</button>
</form>
@endsection