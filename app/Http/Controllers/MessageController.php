<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Events\MessageSent; // Import the MessageSent event
use App\Models\User; // Import the User model

class MessageController extends Controller
{
    /**
     * Store a new message and broadcast it to the receiver.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $receiver_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendMessage(Request $request, $receiver_id)
    {
        // Validate message content
        $validated = $request->validate([
            'message' => 'required|string|max:255', // Ensure message is not too long
        ]);

        // Create and store the message in the database
        $message = Message::create([
            'sender_id' => auth()->id(), // The logged-in user
            'receiver_id' => $receiver_id, // The user receiving the message
            'message' => $request->message, // The actual message content
        ]);

        // Broadcast the message to the receiver's channel
        broadcast(new MessageSent($message)); // This triggers the event and broadcasts the message

        // Redirect back to the chat page with a success message
        return redirect()->route('messages.fetch', $receiver_id)->with('success', 'Message sent!');
    }

    /**
     * Fetch all messages between the authenticated user and a specific receiver.
     *
     * @param int $receiver_id
     * @return \Illuminate\View\View
     */
    public function fetchMessages($receiver_id)
    {
        // Fetch the messages between the authenticated user and the receiver
        $messages = Message::where(function($query) use ($receiver_id) {
            $query->where('sender_id', auth()->id())
                  ->where('receiver_id', $receiver_id);
        })
        ->orWhere(function($query) use ($receiver_id) {
            $query->where('sender_id', $receiver_id)
                  ->where('receiver_id', auth()->id());
        })
        ->orderBy('created_at', 'asc')  // Order messages by creation date
        ->get();
    
        // Fetch the receiver's information (the user being chatted with)
        $receiver = User::findOrFail($receiver_id); // Fetch user by receiver_id
    
        // Pass both the messages and receiver data to the view
        return view('messages.chat', compact('messages', 'receiver'));
    }
    
}
