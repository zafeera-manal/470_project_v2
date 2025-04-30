@extends('layouts.app')

@section('content')
<div class="container py-5">
    <!-- Chat Box Container with reduced width and margin-top -->
    <div class="card shadow-lg mx-auto" style="max-width: 600px; margin-top: 100px; position: relative;">
        
        <!-- Close Button in Top Right Corner -->
        <a href="{{ route('friends.index') }}" class="btn btn-danger btn-sm" style="position: absolute; top: 10px; right: 10px;">&times;</a>

        <div class="card-header text-center">
            <h2 class="h4 text-dark">Chat with {{ $receiver->name }}</h2>
        </div>
        <div class="card-body" style="max-height: 400px; overflow-y: auto;">
            <!-- Messages List -->
            <div class="d-flex flex-column">
                @foreach ($messages as $message)
                    <div class="d-flex {{ $message->sender_id == auth()->id() ? 'justify-content-end' : 'justify-content-start' }}">
                        <div class="p-3 rounded-3 {{ $message->sender_id == auth()->id() ? 'bg-primary text-white' : 'bg-light' }}" style="max-width: 75%; word-wrap: break-word;">
                            <p class="mb-0 text-dark">{{ $message->message }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="card-footer">
            <!-- Message Input and Send Button -->
            <form action="{{ route('messages.send', $receiver->id) }}" method="POST" class="d-flex">
                @csrf
                <input type="text" name="message" class="form-control mr-2" placeholder="Type your message..." required>
                <button type="submit" class="btn btn-primary">Send</button>
            </form>
        </div>
    </div>
</div>
@endsection
