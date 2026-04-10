@forelse ($friendRequests as $f)
    <p> {{ $f->sender->name }}</p>
    <p> -- status : {{ $f->status }}</p>

    @if ($f->status == 'pending')
        <form action="{{ route('requests.accept', $f->id) }}" method="POST">
            @csrf
            <button>accept</button>
        </form>

        <form action="{{ route('requests.reject', $f->id) }}" method="POST">
            @csrf
            <button>reject</button>
        </form>
    @endif

    <form action="{{ route('requests.destroy', $f->id) }}" method="POST">
        @csrf
        @method('DELETE')
        <button>delete</button>
    </form>

    <br>

@empty

    <p>there is no pending received req</p>
@endforelse
