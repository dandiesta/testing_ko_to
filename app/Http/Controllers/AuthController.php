<?php namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;

class AuthController extends Controller {

    public function login()
    {
        return view('pages.login');
    }

    public function authenticate()
    {
        $input = Request::all();

        $hash = Hash::make($input['password']);
        dd($hash);

        return view('pages.login');
    }
}
