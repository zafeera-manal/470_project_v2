<?php

namespace App\Http\Controllers;

use App\Models\GroupTrip;
use App\Models\GroupTripInvitation;
use App\Models\User;
use App\Models\City\City; // Assuming you have a City model
use Illuminate\Http\Request;

class GroupTripController extends Controller
{
    public function create()
    {
        // Fetch all cities from the database
        $cities = City::all(); // Assuming you have a City model and cities table
    
        // Get the logged-in user's friends (assuming 'friends' relationship is defined in the User model)
        $friends = auth()->user()->friends; 
    
        return view('group_trips.create', compact('cities', 'friends'));  // Pass cities and friends to the view
    }
    
    public function store(Request $request)
    {
        dd($request->all());

        $request->validate([
            'name' => 'required',
            'destination' => 'required|exists:cities,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'description' => 'nullable|string',
            'friends' => 'nullable|array', // Validate friends as an array
            'friends.*' => 'exists:users,id', // Validate each friend ID exists in the users table
        ]);
    
        // Create the group trip
        $groupTrip = GroupTrip::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'destination' => $request->destination,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'description' => $request->description,
        ]);
    
        // Invite friends
        if ($request->has('friends')) {
            $friends = collect($request->input('friends', []))
                ->filter() // Removes null, false, empty string
                ->unique(); // Prevent duplicate IDs
            foreach ($request->friends as $friendId) {
                GroupTripInvitation::create([
                    'group_trip_id' => $groupTrip->id,
                    'friend_id' => $friendId,
                    'status' => 'pending', // Default status is 'pending'
                ]);
            }
        }
    
        return redirect()->route('group_trips.index')->with('success', 'Group trip created and invitations sent!');
    }

    // Send invitations to friends
    public function sendInvitations(Request $request, $groupTripId)
    {
        $groupTrip = GroupTrip::findOrFail($groupTripId);
        $friends = $request->input('friends');  // Array of friend ids

        foreach ($friends as $friendId) {
            GroupTripInvitation::create([
                'group_trip_id' => $groupTrip->id,
                'friend_id' => $friendId,
                'status' => 'pending',
            ]);
        }

        return redirect()->route('group_trips.show', $groupTripId)->with('success', 'Invitations sent successfully!');
    }

    public function viewInvitations()
    {
        // Get all invitations for the logged-in user
        $invitations = GroupTripInvitation::where('friend_id', auth()->id())
                                           ->where('status', 'pending')
                                           ->get();
    
        return view('group_trips.invitations', compact('invitations'));
    }

    // Accept an invitation
    public function acceptInvitation($invitationId)
    {
        $invitation = GroupTripInvitation::findOrFail($invitationId);
        $invitation->status = 'confirmed';
        $invitation->save();

        return redirect()->route('group_trips.show', $invitation->group_trip_id)->with('success', 'Invitation accepted!');
    }

    // Reject an invitation
    public function rejectInvitation($invitationId)
    {
        $invitation = GroupTripInvitation::findOrFail($invitationId);
        $invitation->status = 'declined';
        $invitation->save();

        return redirect()->route('group_trips.show', $invitation->group_trip_id)->with('success', 'Invitation declined!');
    }
}
