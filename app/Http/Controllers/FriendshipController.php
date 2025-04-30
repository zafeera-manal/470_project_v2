<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Friendship;
use Illuminate\Http\Request;

class FriendshipController extends Controller
{
    // View friends, pending requests, and users to add as friends
    public function viewFriends()
    {
        // Fetch the authenticated user's friends
        $friends = auth()->user()->friends;
    
        // Get users to add as friends (excluding the current user and already accepted friends)
        $usersToAdd = User::where('id', '!=', auth()->id())
                            ->whereNotIn('id', function ($query) {
                                $query->select('friend_id')
                                        ->from('friendships')
                                        ->where('user_id', auth()->id())
                                        ->where('status', 'accepted');
                            })
                            ->get()
                            ->map(function ($user) {
                                // Set the 'request_sent' flag if a pending request exists
                                $user->request_sent = Friendship::where('user_id', auth()->id())
                                                                ->where('friend_id', $user->id)
                                                                ->where('status', 'pending')
                                                                ->exists();
                                return $user;
                            });
    
        // Fetch pending requests (where current user is the friend)
        $pendingRequests = Friendship::where('friend_id', auth()->id())
                                     ->where('status', 'pending')
                                     ->get();
    
        return view('friends.index', compact('friends', 'usersToAdd', 'pendingRequests'));
    }
    
    

    // Method to send a friend request
    public function sendRequest($friend_id)
    {
        // Ensure the current user isn't sending a request to themselves
        if ($friend_id == auth()->id()) {
            return redirect()->back()->withErrors(['error' => 'You cannot send a friend request to yourself.']);
        }
    
        // Check if the user has already sent a request or is already friends
        $existingFriendship = Friendship::where(function($query) use ($friend_id) {
            $query->where('user_id', auth()->id())
                  ->where('friend_id', $friend_id);
        })->orWhere(function($query) use ($friend_id) {
            $query->where('user_id', $friend_id)
                  ->where('friend_id', auth()->id());
        })->first();
    
        if ($existingFriendship) {
            // If already friends or request is pending, display a message
            return redirect()->back()->withErrors(['error' => 'You are already friends or have already sent a request.']);
        }
    
        // Create the friend request
        $friendship = Friendship::create([
            'user_id' => auth()->id(),
            'friend_id' => $friend_id,
            'status' => 'pending',
        ]);
    
        return redirect()->route('friends.index')->with('success', 'Friend request sent!');
    }

    
    public function undoRequest($friend_id)
    {
        // Find the pending friendship request
        $friendship = Friendship::where('user_id', auth()->id())
                                ->where('friend_id', $friend_id)
                                ->where('status', 'pending')
                                ->first();
    
        if ($friendship) {
            $friendship->delete();  // Delete the pending request
            return redirect()->route('friends.index')->with('success', 'Friend request undone!');
        }
    
        return redirect()->route('friends.index')->withErrors(['error' => 'No pending request found to undo.']);
    }
    
    


    // View pending friend requests
    public function viewPendingRequests()
    {
        $pendingRequests = Friendship::where('friend_id', auth()->id())
                                    ->where('status', 'pending')
                                    ->get();

        return view('friends.pending', compact('pendingRequests'));
    }

    // Accept a friend request
    public function acceptRequest($friendship_id)
    {
        $friendship = Friendship::findOrFail($friendship_id);
    
        // Only the friend who received the request can accept it
        if ($friendship->friend_id !== auth()->id()) {
            return redirect()->route('friends.pending')->withErrors(['error' => 'You cannot accept this request.']);
        }
    
        // Update the status to 'accepted'
        $friendship->status = 'accepted';
        $friendship->save();
    
        // Create the reciprocal friendship for user1 if it doesn't exist
        $existingFriendship = Friendship::where('user_id', $friendship->user_id)
                                         ->where('friend_id', auth()->id())
                                         ->first();
    
        if (!$existingFriendship) {
            Friendship::create([
                'user_id' => $friendship->user_id,
                'friend_id' => auth()->id(),
                'status' => 'accepted',
            ]);
        }
        $existingFriendship2 = Friendship::where('friend_id', $friendship->user_id)
                                         ->where('user_id', auth()->id())
                                         ->first();
        
        if (!$existingFriendship2) {
            Friendship::create([
                'user_id' => auth()->id(),
                'friend_id' => $friendship->user_id,
                'status' => 'accepted',
            ]);
        }
    
        // Redirect back to the pending requests page with a success message
        return redirect()->route('friends.pending')->with('success', 'Friend request accepted!');
    }
    

    // Reject a friend request
    public function rejectRequest($friendship_id)
    {
        $friendship = Friendship::findOrFail($friendship_id);
        
        // Only the friend who received the request can reject it
        if ($friendship->friend_id !== auth()->id()) {
            return redirect()->route('friends.pending')->withErrors(['error' => 'You cannot reject this request.']);
        }

        // Delete the friendship request (no longer pending)
        $friendship->delete();

        return redirect()->route('friends.pending')->with('success', 'Friend request rejected!');
    }


    public function searchFriends(Request $request)
    {
        // Get the search query from the request
        $search = $request->input('search');
    
        // If there's a search term, filter users by name
        $usersToAdd = User::where('name', 'like', '%' . $search . '%')
        ->where('id', '!=', auth()->id()) // Exclude the current user
        ->whereNotIn('id', function ($query) {
            $query->select('friend_id')
                ->from('friendships')
                ->where('user_id', auth()->id())
                ->where('status', 'accepted');  // Exclude already accepted friends
        })
        ->get();
    
        // Fetch the current user's friends
        $friends = auth()->user()->friends;
    
        // Return the view with the search results
        return view('friends.index', compact('friends', 'usersToAdd'));
    }
    
}
