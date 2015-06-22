<?php

namespace App\Http\Controllers;

use App\ApplicationOwner;
use App\UserPass;
use App\Application;
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
