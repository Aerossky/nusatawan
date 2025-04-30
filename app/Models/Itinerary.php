<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Itinerary extends Model
{
    //
    use HasFactory, Sluggable;

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

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title',
                'onUpdate' => true,
            ]
        ];
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }


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
