@forelse ($categories as $c )
    <p>{{ $c->title}}</p>
@empty
    <p>no cat created yet</p>
@endforelse