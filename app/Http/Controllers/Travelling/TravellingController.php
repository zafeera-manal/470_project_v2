<?php

namespace App\Http\Controllers\Travelling;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\City\City;
use App\Models\Country\Country;

class TravellingController extends Controller
{
    //
    public function about($id)
    {
        $cities = City::select()->orderBy('id', 'desc')->take(5)->where('country_id', $id)->get();

        $country = Country::find($id);

        return view('travelling.about', compact('cities', 'country'));
    }
}
