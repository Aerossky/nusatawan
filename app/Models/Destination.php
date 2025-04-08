<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{

    // Nama tabel (opsional, jika nama tabel berbeda dari jamak model)
    protected $table = 'destinations';

    // Kolom yang dapat diisi
    protected $fillable = [
        'place_name',
        'description',
        'category_id',
        'city',
        'rating',
        'rating_count',
        'latitude',
        'longitude'
    ];

    // Relasi dengan Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relasi dengan Destination Images
    public function images()
    {
        return $this->hasMany(DestinationImage::class);
    }

    // Relasi dengan Reviews
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Relasi dengan Liked Destinations
    public function likedByUsers()
    {
        return $this->hasMany(LikedDestination::class);
    }

    // Relasi dengan Itinerary Destinations
    public function itineraryDestinations()
    {
        return $this->hasMany(ItineraryDestination::class);
    }
}
