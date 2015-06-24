<?php

namespace App\Http\Controllers;

use App\UserPass;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        $email_exists = UserPass::where(DB::raw('BINARY mail'), $input['email'])->get();

        if($email_exists->isEmpty()) {
            return redirect()->back()
                ->with('msg', 'Invalid Email/Password')
                ->withInput();
        }

        if (Auth::attempt(['mail' => $input['email'], 'password' => $input['password']])) {
            return redirect()->route('top_apps');
        }

        return redirect()->back()
            ->with('msg', 'Invalid Email/Password')
            ->withInput();;
    }
}
