<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'is_admin'
    ];

    // Relasi dengan Destinations
    public function destinations()
    {
        return $this->hasMany(Destination::class);
    }
}
