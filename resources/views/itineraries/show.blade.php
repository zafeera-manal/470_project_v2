@extends('layouts.app')

@section('content')
<div class="container py-5">
    <!-- Itinerary Title -->
    <h1 class="display-4 font-weight-bold text-dark text-center mb-5">{{ $itinerary->destination }} Itinerary</h1>

    <!-- Itinerary Information Card -->
    <div class="card shadow-lg rounded-lg mb-5">
        <div class="card-body">
            <!-- Start Date -->
            <p class="lead mb-2"><strong>Start Date:</strong> <span class="text-muted">{{ \Carbon\Carbon::parse($itinerary->start_date)->format('F d, Y') }}</span></p>
            <!-- End Date -->
            <p class="lead mb-2"><strong>End Date:</strong> <span class="text-muted">{{ \Carbon\Carbon::parse($itinerary->end_date)->format('F d, Y') }}</span></p>
            <!-- Budget -->
            <p class="lead mb-2"><strong>Budget:</strong> <span class="text-success">${{ number_format($itinerary->budget, 2) }}</span></p>
            <!-- Description -->
            <p class="lead"><strong>Description:</strong> <span class="text-muted">{{ $itinerary->description }}</span></p>
        </div>
    </div>

    <!-- City Stops Section -->
    <h3 class="h4 font-weight-bold text-dark mb-4">City Stops</h3>
    
    @if($itinerary->cityStops->isEmpty())
        <div class="alert alert-info text-center">
            No city stops added.
        </div>
    @else
        <ul class="list-group">
            @foreach($itinerary->cityStops as $cityStop)
                <li class="list-group-item d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="mb-1 text-primary">{{ $cityStop->name }}</h5>
                        <p class="mb-1 text-muted">{{ $cityStop->description }}</p>
                    </div>
                    <div>
                        <button class="btn btn-outline-primary btn-sm">Details</button>
                    </div>
                </li>
            @endforeach
        </ul>
    @endif

    <!-- Share Button (Multi-Select) -->
    <!-- Share Button (Email) -->
    <h3 class="h4 font-weight-bold text-dark text-center mt-5 mb-4">Share this itinerary with friends</h3>
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-sm rounded-3 p-4">
                <form action="{{ route('itineraries.share', ['itinerary_id' => $itinerary->id]) }}" method="POST">
                    @csrf
                    <!-- Friend Selection -->
                    <div class="mb-3">
                        <label for="friend_id" class="form-label">Select a Friend</label>
                        <select name="friend_id" id="friend_id" class="form-select form-select-lg @error('friend_id') is-invalid @enderror" required>
                            <option value="" disabled selected>Select a friend</option>
                            @foreach($friends as $friend)
                                <option value="{{ $friend->id }}">{{ $friend->name }}</option>
                            @endforeach
                        </select>
                        @error('friend_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary w-100 py-2">Share Itinerary</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Action Button Section -->
    <div class="text-center mt-4">
        <a href="{{ route('itineraries.index') }}" class="btn btn-secondary btn-lg px-4 py-2">Back to Itineraries</a>
    </div>
</div>

@endsection
