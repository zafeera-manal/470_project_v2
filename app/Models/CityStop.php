<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CityStop extends Model
{
    protected $fillable = ['name', 'description', 'itinerary_id', 'city_id'];

    public function itinerary()
    {
        return $this->belongsTo(Itinerary::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
