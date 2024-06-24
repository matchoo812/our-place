<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RouteController extends Controller
{
    public function homepage()
    {
        return view('homepage');
    }

    public function aboutPage()
    {
        return view('single-post');
    }
}
