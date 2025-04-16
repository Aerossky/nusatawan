<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DestinationSubmissionController extends Controller
{
    //
    public function index()
    {

        return view('user.destination-submission');
    }

    public function store(Request $request)
    {
        // Redirect or return a response
    }
}
