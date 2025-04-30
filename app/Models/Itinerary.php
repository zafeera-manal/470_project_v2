<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\City\City;


class Itinerary extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'destination', 'start_date', 'end_date', 'budget', 'description'
    ];

    // Each itinerary belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Each itinerary can have many points of interest
    public function pointsOfInterest()
    {
        return $this->hasMany(PointOfInterest::class);
    }



        // Define the relationship with the City model
    public function city()
    {
        return $this->belongsTo(City::class, 'destination'); // 'destination' is the foreign key
    }


        // Define the relationship with city stops
    public function cityStops()
    {
        return $this->hasMany(CityStop::class);
    }

    public function sharedWithUsers()
    {
        return $this->belongsToMany(User::class, 'shared_itineraries')->withTimestamps();
    }
}