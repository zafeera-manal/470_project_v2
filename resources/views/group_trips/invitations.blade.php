<!-- resources/views/group_trip/invitations.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h1 class="text-center mb-4">Your Invitations</h1>

    @if($invitations->isEmpty())
        <p class="text-center">You don't have any invitations.</p>
    @else
        <div class="list-group">
            @foreach($invitations as $invitation)
                <div class="list-group-item">
                    <h5>{{ $invitation->groupTrip->name }}</h5>
                    <p>From: {{ $invitation->groupTrip->start_date }} to {{ $invitation->groupTrip->end_date }}</p>
                    <form action="{{ route('group_trips.acceptInvitation', $invitation->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm">Accept</button>
                    </form>
                    <form action="{{ route('group_trips.rejectInvitation', $invitation->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                    </form>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
