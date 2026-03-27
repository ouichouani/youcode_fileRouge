


<div style="max-width: 700px; margin: 30px auto; font-family: Arial, sans-serif;">
    <h1>habit Details</h1>

    <div style="padding: 20px; border: 1px solid #ddd; border-radius: 10px;">
        <p><strong>ID:</strong> {{ $habit->id }}</p>
        <p><strong>Title:</strong> {{ $habit->title }}</p>
        <p><strong>Description:</strong> {{ $habit->description ?: 'No description' }}</p>
        <p><strong>Difficulty:</strong> {{ $habit->difficulty }}</p>
        <p><strong>Priority:</strong> {{ $habit->priority }}</p>

        <p>
            <strong>Deadline:</strong>
            {{ $habit->deadline ? $habit->deadline->format('Y-m-d H:i') : 'No deadline' }}
        </p>

        <p><strong>Status:</strong> {{ $habit->done ? 'Done' : 'Not done' }}</p>
        <p><strong>Streaks:</strong> {{ $habit->streaks ?? 0 }}</p>

        <p>
            <strong>Frequency:</strong>
            @if (!empty($habit->frequency) && is_array($habit->frequency))
                {{ implode(', ', $habit->frequency) }}
            @else
                No frequency set
            @endif
        </p>

        <p><strong>Category:</strong> {{ $habit->category?->title ?? 'No category' }}</p>
        <p><strong>User ID:</strong> {{ $habit->user_id }}</p>
        <p><strong>Created At:</strong> {{ $habit->created_at?->format('Y-m-d H:i') }}</p>
        <p><strong>Updated At:</strong> {{ $habit->updated_at?->format('Y-m-d H:i') }}</p>

        <a href='{{ route('habits.edit' , $habit->id) }}'>update</a>
        <form action='{{ route('habits.destroy' , $habit->id) }}' method='post'>
            @method('DELETE')
            @csrf
            <button>delete</button>
        </form>
    </div>
</div>
