@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="display-4 font-weight-bold text-dark">My Friends</h1>
        
    </div>

    <!-- Search Bar to Search Friends -->
    <form action="{{ route('friends.search') }}" method="GET" class="mb-4">
        <div class="input-group input-group-lg" style="max-width: 500px; margin: 0 auto;">
            <input type="text" name="search" class="form-control form-control-lg" placeholder="Search for users by name" value="{{ request('search') }}" aria-label="Search for users by name">
            <div class="input-group-append">
                <button class="btn btn-info btn-lg" type="submit">Search</button>
            </div>
        </div>
    </form>




    <!-- Button to go to Pending Friend Requests -->
    <div class="text-center mb-4">
        <a href="{{ route('friends.pending') }}" class="btn btn-info btn-lg">View Pending Friend Requests</a>
    </div>

    <!-- Current Friends Section -->
    @if ($friends->isEmpty())
        <div class="bg-white p-4 rounded shadow-sm text-center">
            <p class="h5 text-secondary">You don't have any friends yet.</p>
        </div>
    @else
        <div class="mb-5">
            <h3 class="h4 font-weight-semibold text-dark mb-4">My Friends List</h3>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                @foreach($friends as $friend)
                    <div class="col">
                        <div class="card shadow-sm">
                            <div class="card-body text-center">
                                <!-- Avatar Image -->
                                <img src="{{ $friend->avatar_url ?? 'https://picsum.photos/150' }}" alt="Avatar" class="img-fluid rounded-circle mb-3" style="width: 80px; height: 80px; object-fit: cover;">
                                <h5 class="card-title">{{ $friend->name }}</h5>
                                <p class="card-text text-muted">{{ $friend->email }}</p>
                                <a href="{{ route('messages.fetch', $friend->id) }}" class="btn btn-outline-primary btn-sm">Chat</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Users You Can Add as Friends Section -->
    <div class="bg-white p-4 rounded shadow-sm">
        <h2 class="h4 font-weight-semibold text-dark mb-4">Find more friends</h2>
        @if ($usersToAdd->isEmpty())
            <p class="h5 text-secondary">No users available to add as friends.</p>
        @else
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                @foreach($usersToAdd as $user)
                    <div class="col">
                        <div class="card shadow-sm">
                            <div class="card-body text-center">
                                <!-- Avatar Image -->
                                <img src="{{ $user->avatar_url ?? 'https://picsum.photos/150' }}" alt="Avatar" class="img-fluid rounded-circle mb-3" style="width: 80px; height: 80px; object-fit: cover;">
                                <h5 class="card-title">{{ $user->name }}</h5>
                                <p class="card-text text-muted">{{ $user->email }}</p>

                                <!-- Conditionally Display Buttons -->
                                @if($user->request_sent)
                                    <!-- Undo Friend Request Button -->
                                    <form action="{{ route('friends.undo', $user->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-warning btn-sm">Undo Request</button>
                                    </form>
                                @else
                                    <!-- Send Friend Request Button -->
                                    <form action="{{ route('friends.sendRequest', $user->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-primary btn-sm">Send Friend Request</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
