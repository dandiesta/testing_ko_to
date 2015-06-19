<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;

class AuthController extends Controller
{

    public function login()
    {
        return view('pages.login');
    }

    public function authenticate()
    {
        $input = Request::all();



        return view('pages.login');
    }
}
