<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DestinationSubmission extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'place_name',
        'description',
        'city',
        'time_minutes',
        'latitude',
        'longitude',
        'status',
        'admin_note'
    ];

    // Relasi dengan User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi dengan Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relasi dengan Destination Submission Images
    public function images()
    {
        return $this->hasMany(DestinationSubmissionImage::class);
    }
}
