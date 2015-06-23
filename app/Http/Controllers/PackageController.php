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
use App\InstallLog;

class PackageController extends Controller
{
    public function index(Helper $helper)
    {
        $id = Request::input('id');
        $app = Package::selectByPackageId($id);
        $app->tags = $helper->getArrayable(Tag::selectByPackageId($id));
        $app->user_count = count(Package::installedUsers($app->id));
        $app->owners = Application::getOwnersByAppId($app->app_id);
        $app->is_installed = Package::isInstalled($id);
        $app->last_date_installed = Package::lastDateInstalled($id);
        $app->is_owner = Application::checkUserOwnerByAppId(Auth::user()->mail, $app->app_id);
        $app->installed_users = Package::installedUsers($app->id);
        
        $data = [
            'app' => $app,
            'current_page' => Route::currentRouteName()
        ];

        return view('pages.packages.index', $data);
    }

    public function edit(Helper $helper)
    {
        $id = Request::input('id');
        $app = Package::selectByPackageId($id);
        $app->all_tags = Tag::getAll($app->app_id);
        $app->package_tags = $helper->getArrayable(Tag::selectByPackageId($id));

        $data = [
            'app' => $app,
            'current_page' => Route::currentRouteName()
        ];

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

    public function install()
    {
        $package_id = Request::input('id');
        $package = Package::find($package_id);
        $url = $package->install_url;

        if ($package->platform === 'iOS' && Helper::isIOSmobile()) {
            $params = [
                'id' => $package->id
            ];
            $plist_url = route('install_plist', $params);
            $url = 'itms-services://?action=download-manifest&url='.$plist_url;
        }

        $data = [
            'app_id' => $package->app_id,
            'package_id' => $package->id,
            'mail' => Auth::user()->mail,
            'user_agent' => $_SERVER['HTTP_USER_AGENT'],
            'installed' => date('Y-m-d H:i:s'),
        ];
        $log = new InstallLog($data);
        $log->save();

        return redirect()->to($url);
    }

    public function install_plist()
    {
        $package_id = Request::input('id');
        $package = Package::find($package_id);
        $app = Application::find($package->app_id);

        $xml = '<?xml version="1.0" encoding="UTF-8"?>
                <!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">
                <plist version="1.0">
                  <dict>
                    <key>items</key>
                    <array>
                      <dict>
                        <key>assets</key>
                        <array>
                          <dict>
                            <key>kind</key>
                            <string>software-package</string>
                            <key>url</key>
                            <string>__IPA_URL__</string>
                          </dict>
                          <dict>
                            <key>kind</key>
                            <string>full-size-image</string>
                            <key>needs-shine</key>
                            <true/>
                            <key>url</key>
                            <string>__IMAGE_URL__</string>
                          </dict>
                          <dict>
                            <key>kind</key>
                            <string>display-image</string>
                            <key>needs-shine</key>
                            <true/>
                            <key>url</key>
                            <string>__IMAGE_URL__</string>
                          </dict>
                        </array>
                        <key>metadata</key>
                        <dict>
                          <key>bundle-identifier</key>
                          <string>__BUNDLE_IDENTIFIER__</string>
                          <key>bundle-version</key>
                          <string>1.0</string>
                          <key>kind</key>
                          <string>software</string>
                          <key>subtitle</key>
                          <string>__PKG_TITLE__</string>
                          <key>title</key>
                          <string>__APP_TITLE__</string>
                        </dict>
                      </dict>
                    </array>
                  </dict>
                </plist>';

        $ipa_url = $package->install_url;
        $image_url = env('AWS_URL') . $app->icon_key;
        $bundle_identifier = $package->ios_identifier;
        $pkg_title = $package->title;
        $app_title = $app->title;

        $search = ['__IPA_URL__', '__IMAGE_URL__', '__BUNDLE_IDENTIFIER__', '__PKG_TITLE__', '__APP_TITLE__'];
        $replace = [$ipa_url, $image_url, $bundle_identifier, $pkg_title, $app_title];

        $xml = str_replace($search, $replace, $xml);
        $header = array(
            'Content-Type: text/xml',
        );

        return array($header,$xml);
    }
}
