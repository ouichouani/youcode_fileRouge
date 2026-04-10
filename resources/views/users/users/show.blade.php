


<p>{{$user->name}}</p>

<p> - followers : {{ count($sentRequests) }}</p> 
<p> - followed by : {{ count($receivedRequests) }}</p>  


@if (isset($pendingRequest) && $pendingRequest?->status == 'pending')

@if($pendingRequest?->sender_id == Auth::id())
    <p> - you sent a friend request to  {{  $pendingRequest?->sender->name }} </p>
    <form action="{{ route('requests.destroy' , $pendingRequest->id) }}" method="POST">
        @csrf
        @method('DELETE')
        <button>cansel</button>
    </form>
@endif

@if($pendingRequest?->receiver_id == Auth::id())
    <p> - {{  $pendingRequest?->sender->name }} send a friend request </p>
    <form action="{{ route('requests.reject' , $pendingRequest->id) }}" method="POST">
        @csrf
        <button>reject</button>
    </form>
    <form action="{{ route('requests.accept' , $pendingRequest->id) }}" method="POST">
        @csrf
        <button>accept</button>
    </form>
@endif


@endif
<p>---------------posts------------</p>

@foreach ( $posts as $p )
    <p>{{$p->content}}</p>
    <p>likes : {{count($p->likes)}}</p>

    @foreach ($p->comments as $c)
        <p> -- {{ $c->content }}</p>    
    @endforeach
@endforeach