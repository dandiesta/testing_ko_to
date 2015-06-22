<?php

namespace App\Http\Controllers;

# general
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request;

# models
use App\Package;
use App\Tag;
use App\User;

class PackageController extends Controller
{
    public function index()
    {
        $id = Request::input('id');
        $data['app'] = Package::selectByPackageId($id);
        $data['tags'] = Tag::selectByPackageId($id);
        $data['user_count'] = User::countUserPerPackage($id);

        $data['current_page'] = Route::currentRouteName();
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