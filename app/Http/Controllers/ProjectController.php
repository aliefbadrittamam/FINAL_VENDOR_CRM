<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        return view('projects.index');
    }

    public function timeline()
    {
        return view('projects.timeline');
    }

    public function status()
    {
        return view('projects.status');
    }
}