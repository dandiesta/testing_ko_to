<?php

namespace App\Http\Controllers;

class ApplicationController extends Controller
{
    public function package()
    {
        return view('pages.packages.index');
    }

    public function index()
    {
        return view('pages.app_index');
    }
}