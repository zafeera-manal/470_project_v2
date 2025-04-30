<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupTrip extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'created_by', 'destination', 'start_date', 'end_date', 'description'];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function invitations()
    {
        return $this->hasMany(GroupTripInvitation::class);
    }
}