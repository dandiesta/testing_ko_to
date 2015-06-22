<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function login()
    {
        return view('pages.login');
    }

    public function logout()
    {
        Auth::logout();

        return view('pages.login');
    }

    public function authenticate()
    {
        $input = Request::all();

        if (Auth::attempt(['mail' => $input['email'], 'password' => $input['password']])) {
            return redirect()->route('top_apps');
        }

        return redirect()->route('login');
    }
}
