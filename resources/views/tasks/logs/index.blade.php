

@forelse ($logs as $l )
    <p> - {{$l->created_at}}</p>
@empty
    <p>no logs yet</p>
@endforelse