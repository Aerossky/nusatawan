<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Itinerary extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'startDate',
        'endDate',
        'status'
    ];

    protected $dates = [
        'start_date',
        'end_date'
    ];

    // Relasi dengan User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi dengan Itinerary Destinations
    public function itineraryDestinations()
    {
        return $this->hasMany(ItineraryDestination::class);
    }
}
