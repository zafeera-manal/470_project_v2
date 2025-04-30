<?php

namespace App\Models\City;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    //
    protected $table = 'cities';
    protected $fillable = [
        'name',
        'image',
        'price',
        'num_days',
        'country_id'
    ];
    public $timestamps = true;

        // City can have many itineraries
    public function itineraries()
    {
        return $this->hasMany(Itinerary::class);
    }

        // Define the relationship with city stops
    public function cityStops()
    {
        return $this->hasMany(CityStop::class);
    }
}
