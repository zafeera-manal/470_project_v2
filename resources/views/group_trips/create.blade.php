<!-- resources/views/group_trip/create.blade.php -->

@extends('layouts.app')

@section('content')
<style>
    .modal-backdrop.show {
        opacity: 0;
        display: none !important;
    }
</style>
<div class="container py-5">
    <h1 class="text-center mb-4">Create a Group Trip</h1>

    <form action="{{ route('group_trips.store') }}" id="groupTripForm" method="POST">
        @csrf

        <div class="form-group">
            <label for="name">Group Trip Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="destination">Destination</label>
            <select name="destination" class="form-control" required>
                <option value="" disabled selected>Select a Destination</option>
                @foreach($cities as $city)
                    <option value="{{ $city->id }}">{{ $city->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="start_date">Start Date</label>
            <input type="date" name="start_date" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="end_date">End Date</label>
            <input type="date" name="end_date" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="description">Description (Optional)</label>
            <textarea name="description" class="form-control"></textarea>
        </div>

        <input type="checkbox" name="friends[]" value="2" checked>
        <input type="checkbox" name="friends[]" value="3" checked>
       


        <!-- Button to trigger the modal -->
        <div class="form-group">
            <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#inviteFriendsModal">
                Invite Friends
            </button>
        </div>

        <!-- Display selected friends' names here -->
        <div class="form-group">
            <label for="selectedFriendsList">Selected Friends</label>
            <input type="text" id="selectedFriendsList" class="form-control" readonly>
        </div>

        <!-- Hidden input to store selected friends' IDs -->
        <input type="hidden" name="friends[]" id="selectedFriends">

        <button type="submit" class="btn btn-primary mt-3">Create Group Trip</button>
    </form>
</div>

<!-- Modal to Select Friends -->
<!-- <div class="modal fade" id="inviteFriendsModal" tabindex="-1" aria-labelledby="inviteFriendsModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="inviteFriendsModalLabel">Select Friends to Invite</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            COMMENT Modal body with checkboxes 
                <div class="modal-body">
                    @foreach ($friends as $friend)
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="friend_checkbox" value="{{ $friend->id }}" id="friend_{{ $friend->id }}">
                            <label class="form-check-label" for="friend_{{ $friend->id }}">{{ $friend->name }}</label>
                        </div>
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button type="button" id="saveFriends" class="btn btn-primary">Save</button>
                </div>
        </div>
    </div>
</div> -->



@endsection



@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const saveButton = document.getElementById('saveFriends');
        saveButton?.addEventListener('click', function () {
            const form = document.getElementById('groupTripForm');

            // Clear any old hidden inputs
            document.querySelectorAll('.friend-hidden-input').forEach(el => el.remove());

            const selectedFriendIds = [];
            const selectedFriendNames = [];

            document.querySelectorAll('.friend-checkbox:checked').forEach(function (checkbox) {
                selectedFriendIds.push(checkbox.value);

                const label = document.querySelector(`label[for="${checkbox.id}"]`);
                if (label) selectedFriendNames.push(label.textContent.trim());

                // Add hidden input to form
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'friends[]';
                input.value = checkbox.value;
                input.classList.add('friend-hidden-input');
                form.appendChild(input);
            });

            // Update visible text field
            document.getElementById('selectedFriendsList').value = selectedFriendNames.join(', ');

            // Properly hide the modal
            const modalEl = document.getElementById('inviteFriendsModal');
            const modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) modal.hide();
        });
    });
</script>
@endsection


