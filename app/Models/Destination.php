<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{

    use HasFactory, Sluggable;
    // Nama tabel (opsional, jika nama tabel berbeda dari jamak model)
    protected $table = 'destinations';

    // Kolom yang dapat diisi
    protected $fillable = [
        'created_by',
        'category_id',
        'place_name',
        'description',
        'city',
        'rating',
        'rating_count',
        'time_minutes',
        'latitude',
        'longitude'
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'place_name',
                'onUpdate' => true,
            ]
        ];
    }


    // Relasi dengan Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relasi dengan Destination Images
    public function primaryImage()
    {
        return $this->hasOne(DestinationImage::class)->where('is_primary', true);
    }

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
