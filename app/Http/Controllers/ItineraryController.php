<?php

namespace App\Http\Controllers;

use App\Models\Itinerary;
use App\Models\CityStop;
use App\Models\City\City; // Assuming you have a City model
use Illuminate\Http\Request;
use App\Mail\ItinerarySharedMail;
use Illuminate\Support\Facades\Mail;
use App\Models\User; // Assuming you have a User model

class ItineraryController extends Controller
{

    public function index()
    {
        // Fetch all itineraries with their associated city data
        $itineraries = auth()->user()->itineraries()->with('city')->get();

        // Fetch shared itineraries for the logged-in user (the user who is receiving the shared itineraries)
        $sharedItineraries = auth()->user()->sharedItineraries()->with('city')->get();

        return view('itineraries.index', compact('itineraries', 'sharedItineraries'));
    }

    // Show create itinerary form
    public function create()
    {
        // Fetch all cities from the database
        $cities = City::all(); // Assuming you have a City model and cities table
        
        return view('itineraries.create', compact('cities'));
    }

    // Store the itinerary
    public function store(Request $request)
    {
        $request->validate([
            'destination' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'budget' => 'required|numeric',
            'description' => 'nullable|string',
            'city_stops' => 'nullable|array',
            'city_stops.*.name' => 'required|string',
            'city_stops.*.description' => 'nullable|string',
        ]);

        // Store the itinerary
        $itinerary = Itinerary::create([
            'user_id' => auth()->id(),
            'destination' => $request->destination,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'budget' => $request->budget,
            'description' => $request->description,
        ]);

        // Add city stops to the itinerary
        if ($request->has('city_stops')) {
            foreach ($request->city_stops as $stop) {
                CityStop::create([
                    'name' => $stop['name'],
                    'description' => $stop['description'],
                    'itinerary_id' => $itinerary->id,
                    'city_id' => $itinerary->destination,  // Assuming the destination city is the main city for stops
                ]);
            }
        }

        return redirect()->route('itineraries.index')->with('success', 'Itinerary created successfully!');
    }

    // Method to display the edit form
    public function edit($id)
    {
        $itinerary = Itinerary::with('cityStops')->findOrFail($id);  // Retrieve itinerary by ID
        $cities = City::all();  // Fetch all cities to populate the destination dropdown

        return view('itineraries.edit', compact('itinerary', 'cities'));  // Pass the itinerary and cities to the view
    }


     // Method to update the itinerary
    public function update(Request $request, $id)
    {
        $request->validate([
            'destination' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'budget' => 'required|numeric',
            'description' => 'nullable|string',
            'city_stops' => 'nullable|array',
            'city_stops.*.name' => 'required|string',
            'city_stops.*.description' => 'nullable|string',
        ]);

        $itinerary = Itinerary::findOrFail($id);
        $itinerary->update([
            'destination' => $request->destination,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'budget' => $request->budget,
            'description' => $request->description,
        ]);

            // Add city stops to the itinerary
        if ($request->has('city_stops')) {
            foreach ($request->city_stops as $stop) {
                CityStop::create([
                    'name' => $stop['name'],
                    'description' => $stop['description'],
                    'itinerary_id' => $itinerary->id, // Access the itinerary ID here
                    'city_id' => $itinerary->destination, // Assuming the destination city is the city for stops
                ]);
            }
        }

        return redirect()->route('itineraries.index')->with('success', 'Itinerary updated successfully!');
    }

    public function destroy($id)
    {
        // Delete the itinerary
        $itinerary = Itinerary::findOrFail($id);
        $itinerary->delete();

        return redirect()->route('itineraries.index')->with('success', 'Itinerary deleted successfully.');
    }

        // Show the details of a specific itinerary
    public function show($id)
    {
        // Find the itinerary by ID and eager load city stops and city relationships
        $itinerary = Itinerary::with('cityStops', 'city')->findOrFail($id);

        // Get the friends of the logged-in user
        // Assuming the friends are stored in a pivot table 'friendships' with a status of 'accepted'
        $friends = auth()->user()->friends;  // Assuming `friends` is a relationship method on the User model

        // Return the view with the itinerary data and friends list
        return view('itineraries.show', compact('itinerary', 'friends'));
    }


        // Share itinerary via email
    public function shareWithEmail(Request $request, $itinerary_id, $friend_id)
    {
        // Ensure that the friend is not the same as the user sharing the itinerary
        if ($friend_id == auth()->id()) {
            return redirect()->back()->with('error', 'You cannot share the itinerary with yourself.');
        }

        // Find the itinerary and the friend
        $itinerary = Itinerary::findOrFail($itinerary_id);
        $friend = User::findOrFail($friend_id);

        // Send the email to the friend
        Mail::to($friend->email)->send(new ItinerarySharedMail($itinerary));

        // Redirect back with a success message
        return redirect()->route('itineraries.show', $itinerary_id)->with('success', 'Itinerary shared successfully via email!');
    }

    // app/Http/Controllers/ItineraryController.php

 // app/Http/Controllers/ItineraryController.php

    public function shareWithFriend(Request $request, $itinerary_id)
    {
        // Find the itinerary
        $itinerary = Itinerary::findOrFail($itinerary_id);

        // Validate that the friend_id exists and is not the same as the logged-in user
        $friend = User::findOrFail($request->friend_id);

        if ($friend->id === auth()->id()) {
            return redirect()->route('itineraries.show', $itinerary_id)->with('error', 'You cannot share the itinerary with yourself.');
        }

        // Share the itinerary with the selected friend
        $itinerary->sharedWithUsers()->attach($friend->id);

        return redirect()->route('itineraries.show', $itinerary_id)->with('success', 'Itinerary shared successfully!');
    }



    public function testEmail()
    {
        try {
            \Log::info("Attempting to send test email...");
    
            // Send test email
            Mail::raw('This is a test email to verify the email functionality.', function ($message) {
                $message->to('zafeera218@gmail.com')->subject('Test Email');
            });
    
            \Log::info("Test email sent successfully!");
        } catch (\Exception $e) {
            \Log::error("Error sending test email: " . $e->getMessage());
        }
    
        return response()->json(['message' => 'Test email sent successfully!']);
    }
    
}
