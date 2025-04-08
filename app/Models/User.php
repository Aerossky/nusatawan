<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relasi dengan Destinations
    public function likedDestinations()
    {
        return $this->hasMany(LikedDestination::class);
    }

    // Relasi dengan Reviews
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Relasi dengan Itineraries
    public function itineraries()
    {
        return $this->hasMany(Itinerary::class);
    }

    // Relasi dengan Destination Submissions
    public function destinationSubmissions()
    {
        return $this->hasMany(DestinationSubmission::class);
    }
}
