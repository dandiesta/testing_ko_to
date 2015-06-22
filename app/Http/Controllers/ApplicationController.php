<?php

namespace App\Http\Controllers;

# models
use App\ApplicationOwner;
use App\UserPass;
use App\Application;

# general classes
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Input;

class ApplicationController extends Controller
{
    public function package()
    {
        return view('pages.packages.index');
    }

    public function index() {
//        $app_id = \Request::input('id');
//        $app = Application::getAppById($app_id);
//        $pf = \Request::input('pf');
//        return view('app.index');
    }

    public function top_apps()
    {
        $applications = Application::getTopApps(Auth::user()->mail, 20);
        $paginator = new LengthAwarePaginator(
            $applications->forPage(Input::get('page', 1), 20),
            count(Application::getAllApps()),
            20,
            Paginator::resolveCurrentPage(),
            ['path' => Paginator::resolveCurrentPath()]
        );

        $data['applications'] = $paginator;

        return view('pages.app_index', $data);
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
