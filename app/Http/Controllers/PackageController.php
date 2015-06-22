<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;

class PackageController extends Controller
{
    public function index()
    {
        // sample data
        $data['market'] = 'ios';
        $data['current_page'] = Route::currentRouteName();
        $data['id'] = 1;

        return view('pages.packages.index', $data);
    }

    public function edit()
    {
        // sample data
        $data['market'] = 'ios';
        $data['current_page'] = Route::currentRouteName();
        $data['id'] = 1;

        return view('pages.packages.edit', $data);
    }
}