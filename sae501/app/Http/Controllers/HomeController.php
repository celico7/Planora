<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;

class HomeController extends Controller
{
    public function index()
    {
        $projects = auth()->user() ? auth()->user()->projects()->get() : collect();

        return view('home', compact('projects'));
    }

}

