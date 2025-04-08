<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItineraryDestination extends Model
{
    use HasFactory;

    protected $fillable = [
        'itinerary_id',
        'destination_id',
        'visit_date_time',
        'order_index'
    ];

    protected $dates = [
        'visit_date_time'
    ];

    // Relasi dengan Itinerary
    public function itinerary()
    {
        return $this->belongsTo(Itinerary::class);
    }

    // Relasi dengan Destination
    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }
}
