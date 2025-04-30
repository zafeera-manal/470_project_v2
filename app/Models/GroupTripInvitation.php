<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupTripInvitation extends Model
{
    use HasFactory;

    protected $fillable = ['group_trip_id', 'friend_id', 'status'];

    public function groupTrip()
    {
        return $this->belongsTo(GroupTrip::class);
    }

    public function friend()
    {
        return $this->belongsTo(User::class, 'friend_id');
    }
}
