<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;

class WelcomeController extends Controller
{
    public function index()
    {
        return view('welcome');
    }
}
