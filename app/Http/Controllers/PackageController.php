<?php

namespace App\Http\Controllers;

# general
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request;

# helper
use App\Helper;

# models
use App\Package;
use App\Tag;
use App\User;

class PackageController extends Controller
{
    public function index(Helper $helper)
    {
        $id = Request::input('id');
        $data['app'] = Package::selectByPackageId($id);
        $data['tags'] = $helper->getArrayable(Tag::selectByPackageId($id));
        $data['user_count'] = User::countUserPerPackage($id);

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
                        'name'   => $tag
                    ];

                $new_tag = new Tag($params);
                $new_tag->save();

                Package::addNewTag($new_tag->id, $app->id);
            }
        }
        return redirect()->route('package', ['id' => $app->id]);

    }
}