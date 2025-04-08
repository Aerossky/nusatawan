<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LikedDestination extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'user_id',
        'destination_id'
    ];

    // Relasi dengan User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi dengan Destination
    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }
}
