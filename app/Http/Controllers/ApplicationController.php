<?php

namespace App\Http\Controllers;

# general classes
use Illuminate\Support\Facades\Auth;

# models
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
//        $app_id = \Request::input('id');
//        $app = Application::getAppById($app_id);
//        $pf = \Request::input('pf');
//        return view('app.index');
    }

    public function my_apps()
    {
        $own_apps = Application::getUserAppsByEmail(Auth::user()->mail);
        $data = [
            'own_apps' => $own_apps
        ];
        return view('myApps.own', $data);
    }

    public function installed_apps()
    {
        $installed_apps = Application::getInstalledAppsByEmail(Auth::user()->mail);
        $data = [
            'installed_apps' => $installed_apps
        ];
        return view('myApps.installed', $data);
    }


}
