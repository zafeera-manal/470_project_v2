@extends('layouts.app')

@section('content')
<style>
.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}
</style>

<div class="container py-5">
    <h1 class="display-4 font-weight-bold text-dark text-center mb-4">My Itineraries</h1>

    <!-- Button to Create New Itinerary -->
    <div class="text-center mb-4">
        <a href="{{ route('itineraries.create') }}" class="btn btn-primary btn-lg">Create New Itinerary</a>
    </div>

    <!-- Check if there are itineraries -->
    @if($itineraries->isEmpty())
        <p class="text-center text-muted">You haven't created any itineraries yet.</p>
    @else
        <!-- Iterate through itineraries and display each one in a modern card layout -->
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-3">
            @foreach($itineraries as $itinerary)
                <div class="col">
                    <div class="card shadow-sm rounded-3">
                        <!-- City Image Section: Displaying the city's image from the city relation -->
                        <!-- Check if the city exists and has an image -->
                        @if($itinerary->city && $itinerary->city->image)
                            <img src="{{ asset('assets/images/'.$itinerary->city->image) }}" class="card-img-top" alt="City Image" style="object-fit: cover; height: 150px;">
                        @else
                            <!-- Fallback image if the city or image is not available -->
                            <img src="{{ asset('assets/images/cities-01.jpg') }}" class="card-img-top" alt="Default City Image" style="object-fit: cover; height: 150px;">
                        @endif
                        <div class="card-body p-3">
                            <h5 class="card-title">{{ $itinerary->city->name }}</h5>
                            <p class="card-text text-muted">From: {{ \Carbon\Carbon::parse($itinerary->start_date)->format('F d, Y') }} to {{ \Carbon\Carbon::parse($itinerary->end_date)->format('F d, Y') }}</p>
                            <p class="card-text"><strong>Budget:</strong> ${{ number_format($itinerary->budget, 2) }}</p>
                            <a href="{{ route('itineraries.show', $itinerary->id) }}" class="btn btn-info btn-sm">View</a>
                            <a href="{{ route('itineraries.edit', $itinerary->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            
                            <form action="{{ route('itineraries.destroy', $itinerary->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif


    <!-- Shared Itineraries -->
    <!-- Shared Itineraries Section -->
    <h3 class="h4 font-weight-bold text-dark text-center mt-5 mb-4">Shared Itineraries</h3>
    @if($sharedItineraries->isEmpty())
        <p class="text-center text-muted">No itineraries have been shared with you.</p>
    @else
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-3">
            @foreach($sharedItineraries as $itinerary)
                <div class="col">
                    <div class="card shadow-sm rounded-3">
                        <!-- City Image Section: Displaying the city's image from the city relation -->
                        @if($itinerary->city && $itinerary->city->image)
                            <img src="{{ asset('assets/images/'.$itinerary->city->image) }}" class="card-img-top" alt="City Image" style="object-fit: cover; height: 150px;">
                        @else
                            <img src="{{ asset('assets/images/cities-01.jpg') }}" class="card-img-top" alt="Default City Image" style="object-fit: cover; height: 150px;">
                        @endif
                        <div class="card-body p-3">
                            <h5 class="card-title">{{ $itinerary->city->name }}</h5>
                            <p class="card-text text-muted">From: {{ \Carbon\Carbon::parse($itinerary->start_date)->format('F d, Y') }} to {{ \Carbon\Carbon::parse($itinerary->end_date)->format('F d, Y') }}</p>
                            <p class="card-text"><strong>Budget:</strong> ${{ number_format($itinerary->budget, 2) }}</p>
                            <a href="{{ route('itineraries.show', $itinerary->id) }}" class="btn btn-info btn-sm">View</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

</div>
@endsection
