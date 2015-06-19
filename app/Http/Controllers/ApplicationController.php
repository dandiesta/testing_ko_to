<?php

namespace App\Http\Controllers;

class ApplicationController extends Controller
{
    public function package()
    {
        return view('pages.packages.index');
    }
}