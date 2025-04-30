@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="display-4 font-weight-bold text-dark">Create Itinerary</h1>
    </div>

    <div class="card shadow-sm rounded-lg">
        <div class="card-body">
            <form action="{{ route('itineraries.store') }}" method="POST">
                @csrf

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" name="start_date" id="start_date" class="form-control form-control-lg @error('start_date') is-invalid @enderror" value="{{ old('start_date') }}" required>
                        @error('start_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" name="end_date" id="end_date" class="form-control form-control-lg @error('end_date') is-invalid @enderror" value="{{ old('end_date') }}" required>
                        @error('end_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="destination" class="form-label">Destination</label>
                        <select name="destination" id="destination" class="form-control form-control-lg @error('destination') is-invalid @enderror" required>
                            <option value="" disabled selected>Select a City</option>
                            @foreach($cities as $city)
                                <option value="{{ $city->id }}" {{ old('destination') == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                            @endforeach
                        </select>
                        @error('destination')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="budget" class="form-label">Budget</label>
                        <input type="number" name="budget" id="budget" class="form-control form-control-lg @error('budget') is-invalid @enderror" placeholder="Enter budget" value="{{ old('budget') }}" required>
                        @error('budget')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 mb-4">
                        <label for="description" class="form-label">Description (Optional)</label>
                        <textarea name="description" id="description" class="form-control form-control-lg @error('description') is-invalid @enderror" placeholder="Add any extra details here">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="city_stops" class="form-label">City Stops</label>
                        <div id="city-stop-container">
                            <div class="city-stop-entry">
                                <input type="text" name="city_stops[0][name]" class="form-control mb-2" placeholder="City Stop Name" required>
                                <textarea name="city_stops[0][description]" class="form-control mb-2" placeholder="City Stop Description"></textarea>
                            </div>
                        </div>
                        <button type="button" class="btn btn-info btn-sm" id="add-city-stop">Add City Stop</button>
                    </div>

                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-primary btn-lg px-4 py-2">Create Itinerary</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let cityStopIndex = 1;

    document.getElementById('add-city-stop').addEventListener('click', function() {
        const cityStopContainer = document.getElementById('city-stop-container');
        const cityStopEntry = document.createElement('div');
        cityStopEntry.classList.add('city-stop-entry');
        
        cityStopEntry.innerHTML = `
            <input type="text" name="city_stops[${cityStopIndex}][name]" class="form-control mb-2" placeholder="City Stop Name" required>
            <textarea name="city_stops[${cityStopIndex}][description]" class="form-control mb-2" placeholder="City Stop Description"></textarea>
        `;
        
        cityStopContainer.appendChild(cityStopEntry);
        cityStopIndex++;
    });
</script>

@endsection
