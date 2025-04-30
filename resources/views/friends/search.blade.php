<form action="{{ route('friends.search') }}" method="POST">
    @csrf
    <input type="text" name="search" placeholder="Search for friends by name or email">
    <button type="submit">Search</button>
</form>

@foreach($friends as $friend)
    <div>
        <p>{{ $friend->name }} ({{ $friend->email }})</p>
        <form action="{{ route('friends.sendRequest', $friend->id) }}" method="POST">
            @csrf
            <button type="submit">Send Friend Request</button>
        </form>
    </div>
@endforeach
