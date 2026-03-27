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
    @error('description')
        <div style="color:red;">{{ $message }}</div>
    @enderror
    <br>
    <button>create</button>
</form>
