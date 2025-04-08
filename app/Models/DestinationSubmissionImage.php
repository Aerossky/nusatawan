<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DestinationSubmissionImage extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'destination_submission_id',
        'url'
    ];

    // Relasi dengan Destination Submission
    public function destinationSubmission()
    {
        return $this->belongsTo(DestinationSubmission::class);
    }
}
