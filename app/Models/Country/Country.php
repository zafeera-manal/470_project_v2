<?php

namespace App\Models\Country;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    //
    protected $table = 'countries';
    protected $fillable = [
        'name',
        'population',
        'area',
        'price',
        'description',
        'image', 
        'continent'
    ];
    public $timestamps = true;
    
}