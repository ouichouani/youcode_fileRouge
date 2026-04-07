


<p>{{$user->name}}</p>
@foreach ( $posts as $p )
    <p>{{$p->content}}</p>
    <p>likes : {{count($p->likes)}}</p>

    @foreach ($p->comments as $c)
        <p> -- {{ $c->content }}</p>    
    @endforeach
@endforeach