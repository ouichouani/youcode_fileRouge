
<img src="{{ asset('storage/' . $post?->image?->path) }}" alt="">
<p>content : {{$post->content}} </p>
<p>type : {{$post->type}} </p>
<p>visibility : {{$post->visibility}} </p>
<p>user_id : {{$post->user_id}} </p>
<p>created_at : {{$post->created_at}} </p>

<a href="{{ route('posts.edit' , $post->id) }}"> update</a>
<form action="{{ route('posts.destroy' ,  $post->id) }}">@csrf @method('DELETE')<button>delete</button></form>

<br>
<h2 >likes</h2>
<p>{{ count($likes)}}</p>
<form action="{{ route('likes.save') }}" method="POST" > 
    @csrf 
    <input type="hidden" name="post_id" value="{{ $post->id }}">
    <button>like</button></form>
<br>

<br>
<h2 >comments</h2>
<br>

@forelse ($comments as $c)
    <p>{{$c->content}}</p>
    <form action="{{ route('comments.destroy' , $c->id) }}" method="POST">@csrf @method('DELETE') <button>delete</button></form>
    {{-- <br> --}}
@empty
    <p>no comments</p>
@endforelse


<form action="{{ route('comments.store') }}" method="POST">
    @csrf
    <input type="text" name="content">
    <input type="hidden" name="post_id" value="{{ $post->id }}">
    <button>send</button>
</form>