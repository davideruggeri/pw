<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ComingSoonController extends Controller
{
    public function index($feature = 'Questa funzionalità')
    {
        return view('coming-soon', compact('feature'));
    }
}
