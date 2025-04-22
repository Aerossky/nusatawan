<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProfileService
{
    public function getProfile()
    {
        return Auth::user();
    }
}
