<?php

namespace App\Http\Controllers;

# general
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;

# helper
use App\Helper;

# models
use App\Package;
use App\Tag;
use App\User;
use App\UserPass;
use App\Application;

class PackageController extends Controller
{
    public function index(Helper $helper)
    {
        $id = Request::input('id');
        $data['app'] = Package::selectByPackageId($id);
        $data['tags'] = $helper->getArrayable(Tag::selectByPackageId($id));
        $data['user_count'] = User::countUserPerPackage($id);
        $data['owners'] = Application::getOwnersByAppId($data['app']->app_id);
        $data['installed'] = Package::isInstalled($id);
        $data['last_date_installed'] = Package::lastDateInstalled($id);

        $data['current_page'] = Route::currentRouteName();
        return view('pages.packages.index', $data);
    }

    public function edit(Helper $helper)
    {
        $id = Request::input('id');
        $data['app'] = Package::selectByPackageId($id);
        $data['all_tags'] = Tag::getAll($data['app']->app_id);
        $data['package_tags'] = $helper->getArrayable(Tag::selectByPackageId($id));

        $data['current_page'] = Route::currentRouteName();
        return view('pages.packages.edit', $data);
    }

    public function saveEdit(Helper $helper)
    {
        $input = Request::all();

        $app = Package::find($input['id']);
        $app->title = $input['title'];
        $app->description = $input['description'];
        $app->save();

        $all_tags = $helper->getArrayable(Tag::getAll($app->app_id));
        Package::removeAllTags($app->id);

        foreach ($input['tags'] as $tag) {
            if (array_key_exists($tag, $all_tags)) {
                Package::addNewTag($tag, $app->id);
            } else {
                $params = [
                    'app_id' => $app->app_id,
                    'name' => $tag
                ];

                $new_tag = new Tag($params);
                $new_tag->save();

                Package::addNewTag($new_tag->id, $app->id);
            }
        }
        return redirect()->route('package', ['id' => $app->id]);
    }


    public function delete_confirm()
    {
        $id = Request::input('id');
        $data['app'] = Package::selectByPackageId($id);
        $data['tags'] = Tag::selectByPackageId($id);

        $data['current_page'] = Route::currentRouteName();

        return view('pages.packages.delete', $data);
    }

    public function delete()
    {
        $package_id = Request::input('package_id');
        Package::deleteById($package_id);

        return redirect()->route('app', ['id' => Request::input('app_id')]);
    }

    public function upload()
    {
        $app_id = Request::input('id');

        $data['all_tags'] = Tag::getAll($app_id);
        $app = Application::find($app_id);

        $app->app_id = $app_id;
        $app->app_title = $app->title;
        $app->install_user_count = UserPass::getCountUsersByApp($app_id);
        $app->latest_user_install = Application::getLatestUserInstallDate(Auth::user()->mail, $app_id);
        $app->is_owner = Application::checkUserOwnerByAppId(Auth::user()->mail, $app_id);
        $app->install_user = Application::getInstallUserByAppId($app_id);
        $app->owners = Application::getOwnersByAppId($app_id);
        $app->all_tags = Tag::getAll($app_id);

        $data['app'] = $app;
//        dd($app);
        $data['action'] ='upload';

        $data['current_page'] = Route::currentRouteName();
//        dd($data);
        return view('pages.packages.upload', $data);
    }

}