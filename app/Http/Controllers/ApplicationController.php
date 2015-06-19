<?php

namespace App\Http\Controllers;

use App\ApplicationOwner;
use App\UserPass;
use App\Application;


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

    public function my_apps()
    {
        $user_id = 1;//assuming we can get this from laravel auth
        $user = UserPass::find($user_id);
        $own_apps = Application::getUserAppsById($user->mail);
//        dd($own_apps);
        $data = [
            'own_apps' => $own_apps
        ];
        return view('myApps.own', $data);
    }

}
