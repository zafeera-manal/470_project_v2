@extends('layouts.app')

@section('content')
    <h1>Users You Can Add as Friends</h1>

    @if ($users->isEmpty())
        <p>No users are available to add as friends.</p>
    @else
        <ul>
            @foreach($users as $user)
                <li>
                    <p>{{ $user->name }} ({{ $user->email }})</p>
                    <!-- Form to send a friend request -->
                    <form action="{{ route('friends.sendRequest', $user->id) }}" method="POST">
                        @csrf
                        <button type="submit">Send Friend Request</button>
                    </form>
                </li>
            @endforeach
        </ul>
    @endif

@endsection
