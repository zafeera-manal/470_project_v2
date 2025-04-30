@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="display-4 font-weight-bold text-dark">Edit Itinerary</h1>
    </div>

    <div class="card shadow-sm rounded-lg">
        <div class="card-body">
            <!-- Form to update itinerary -->
            <form action="{{ route('itineraries.update', $itinerary->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" name="start_date" id="start_date" class="form-control form-control-lg @error('start_date') is-invalid @enderror" value="{{ old('start_date', $itinerary->start_date) }}" required>
                        @error('start_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" name="end_date" id="end_date" class="form-control form-control-lg @error('end_date') is-invalid @enderror" value="{{ old('end_date', $itinerary->end_date) }}" required>
                        @error('end_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="destination" class="form-label">Destination</label>
                        <select name="destination" id="destination" class="form-control form-control-lg @error('destination') is-invalid @enderror" required>
                            <option value="" disabled>Select a City</option>
                            @foreach($cities as $city)
                                <option value="{{ $city->id }}" {{ old('destination', $itinerary->destination) == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                            @endforeach
                        </select>
                        @error('destination')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="budget" class="form-label">Budget</label>
                        <input type="number" name="budget" id="budget" class="form-control form-control-lg @error('budget') is-invalid @enderror" value="{{ old('budget', $itinerary->budget) }}" placeholder="Enter budget" required>
                        @error('budget')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 mb-4">
                        <label for="description" class="form-label">Description (Optional)</label>
                        <textarea name="description" id="description" class="form-control form-control-lg @error('description') is-invalid @enderror" placeholder="Add any extra details here">{{ old('description', $itinerary->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-success btn-lg px-4 py-2">Update Itinerary</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
