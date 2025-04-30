<!-- resources/views/group_trip/send_invitations.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h1 class="text-center mb-4">Invite Friends to Join the Trip</h1>

    <form action="{{ route('group_trips.sendInvitations', $groupTrip->id) }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="friends">Select Friends</label>
            <select name="friends[]" class="form-control" multiple>
                @foreach($friends as $friend)
                    <option value="{{ $friend->id }}">{{ $friend->name }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Send Invitations</button>
    </form>
</div>
@endsection
