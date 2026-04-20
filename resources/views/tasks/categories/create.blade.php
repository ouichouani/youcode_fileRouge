@extends('components.layout')

@section('title')
    CREATE CATEGORY
@endsection

@section('content')

<form action="{{ route('categories.store') }}" method="POST">
    @csrf
    <input type="text" name='title' placeholder='title' required>
    @error('title')
        <div style="color:red;">{{ $message }}</div>
    @enderror
    <br>
    <input type="color" name="color" id="">
    @error('color')
        <div style="color:red;">{{ $message }}</div>
    @enderror
    <br>
    <textarea name="description" placeholder='description' id="" cols="30" rows="10"></textarea>

    @can('accessGlobalCategories' , App\Models\Category::class)
    <label for="is_global">set as global categories</label>
    <input type="checkbox" name="is_global" id='is_global'> 
    @endcan
    @error('description')
        <div style="color:red;">{{ $message }}</div>
    @enderror
    <br>
    <button>create</button>
</form>

@endsection