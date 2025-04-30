@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="display-4 font-weight-bold text-dark">Pending Friend Requests</h1>
        <p class="lead text-muted">Here are the friend requests waiting for your approval.</p>
    </div>

    @if ($pendingRequests->isEmpty())
        <div class="bg-white p-4 rounded shadow-sm text-center">
            <p class="h5 text-secondary">You have no pending friend requests.</p>
        </div>
    @else
        <div class="mb-5">
            <h3 class="h4 font-weight-semibold text-dark mb-4">Pending Requests</h3>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                @foreach($pendingRequests as $request)
                    <div class="col">
                        <div class="card shadow-sm">
                            <div class="card-body text-center">
                                <h5 class="card-title">{{ $request->user->name }}</h5>
                                <p class="card-text text-muted">{{ $request->user->email }}</p>
                                <form action="{{ route('friends.accept', $request->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">Accept</button>
                                </form>
                                <form action="{{ route('friends.reject', $request->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
