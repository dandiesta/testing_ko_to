<?php

namespace App\Http\Controllers;

# general
use App\AppInstallUser;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Services\IPAFile;

# helper
use App\Helper;

# models
use App\Package;
use App\Tag;
use App\User;
use App\UserPass;
use App\Application;
use App\InstallLog;
use App\PackageTag;

class PackageController extends Controller
{
    const HTTP_200_OK = "HTTP/1.1 200 OK";
    const HTTP_201_CREATED = "HTTP/1.1 201 Created";
    const HTTP_202_ACCEPTED = "HTTP/1.1 202 Accepted";
    const HTTP_301_MOVEDPERMANENTLY = "HTTP/1.1 301 Moved Permanently";
    const HTTP_302_FOUND = "HTTP/1.1 302 Found";
    const HTTP_400_BADREQUEST = "HTTP/1.1 400 Bad Request";
    const HTTP_401_UNAUTHORIZED = "HTTP/1.1 401 Unauthorized";
    const HTTP_403_FORBIDDEN = "HTTP/1.1 403 Forbidden";
    const HTTP_404_NOTFOUND = "HTTP/1.1 404 Not Found";
    const HTTP_405_METHODNOTALLOWED = "HTTP/1.1 405 Method Not Allowed";
    const HTTP_500_INTERNALSERVERERROR = "HTTP/1.1 500 Internal Server Error";
    const HTTP_503_SERVICEUNAVAILABLE = "HTTP/1.1 503 Service Unavailable";

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
        $app->is_owner = Application::checkUserOwnerByAppId(Auth::user()->mail, $app->app_id);

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
        $app = Package::selectByPackageId($id);
        $data['tags'] = Tag::selectByPackageId($id);
        $app->is_owner = Application::checkUserOwnerByAppId(Auth::user()->mail, $id);
        $data['app'] = $app;

        $data['current_page'] = Route::currentRouteName();

        return view('pages.packages.delete', $data);
    }

    public function delete()
    {
        $package_id = Request::input('package_id');
        Package::deleteById($package_id);

        return redirect()->route('app', ['id' => Request::input('app_id')]);
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
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $install_user = AppInstallUser::findOrNewByMail(Auth::user()->mail, $data);
        $install_user->last_installed = date('Y-m-d H:i:s');
        $install_user->save();
        
        $log = new InstallLog($data);
        $log->save();

        return redirect()->to($url);
    }

    public function install_plist()
    {
        $package_id = Request::input('id');
        $package = Package::find($package_id);
        $app = Application::find($package->app_id);

        $xml = file_get_contents(dirname(__FILE__) . '/plist_xml.txt');

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
        $data['action'] ='upload';
        $data['current_page'] = Route::currentRouteName();

        return view('pages.packages.upload', $data);
    }

    public function upload_temp()
    {
        try {
            $file_info = $_FILES['file'];
            if(!$file_info || !isset($file_info['error']) || $file_info['error']!=UPLOAD_ERR_OK){
                error_log(__METHOD__.'('.__LINE__.'): upload file error: $_FILES[file]='.json_encode($file_info));
                return Helper::jsonResponse(
                    self::HTTP_400_BADREQUEST,
                    array('error'=>'upload_file error: $_FILES[file]='.json_encode($file_info)));
            }
            $file_name = $file_info['name'];
            $file_path = $file_info['tmp_name'];
            $file_type = $file_info['type'];

            $platform = null;
            $mime = $file_type;
            $ext = pathinfo($file_name,PATHINFO_EXTENSION);
            $is_zip = file_get_contents($file_path,false,null,0,4)==="PK\x03\x04";
            if($is_zip && $ext==='apk'){
                $platform = 'Android';
                $mime = 'application/vnd.android.package-archive';
            }
            if($is_zip && $ext==='ipa'){
                $platform = 'iOS';
                $mime = 'application/octet-stream';
            }
            $ios_identifier = null;
            if($platform==='iOS'){
                $plist = IPAFile::parseInfoPlist($file_path);
                $ios_identifier = $plist['CFBundleIdentifier'];
            }

            $temp_name = Helper::randomString(16).".$ext";
            Helper::uploadFile($file_path, 'temp-data/' . $temp_name);

        }
        catch(Exception $e) {
            error_log(__METHOD__.'('.__LINE__.'): '.get_class($e).":{$e->getMessage()}");
            return Helper::jsonResponse(
                self::HTTP_500_INTERNALSERVERERROR,
                array('error'=>$e->getMessage(),'exception'=>get_class($e)));
        }
        return Helper::jsonResponse(
            self::HTTP_200_OK,
            array(
                'file_name' => $file_name,
                'temp_name' => $temp_name,
                'platform' => $platform,
                'ios_identifier' => $ios_identifier,
            ));
    }

    public function post_upload()
    {

        $input = Request::all();

        $rules = [
            'title'     => 'required',
            'platform'  => 'required',
            'temp_name' => 'required',
            'file_name' => 'required',
            'file_size' => 'required',
        ];

        $validation = Validator::make($input, $rules);
        if ($validation->fails()) {
            return redirect()->route('upload')->withInput()->withErrors($validation);
        }

        $app = Application::find($input['app_id']);
        if(!$app){
            return redirect()->route('upload')->with('api_error','App not Found!');
        }

        $input['created_at'] = Carbon::now();
        $input['updated_at'] = Carbon::now();
        $input['original_file_name'] = $input['file_name'];
        $input['file_name'] = $input['temp_name'];
        $package = new Package($input);
        $package->save();
        if (isset($input['tags'])) {
            foreach ($input['tags'] as $key => $tag) {
                $tag_detail = Tag::findOrNewByName($tag, $input['app_id']);
                $tags = new PackageTag(['package_id' => $package->id, 'tag_id' => $tag_detail->id]);
                $tags->save();
                unset($tags);
            }
        }
        $key = '/package/' . $app->id . '/' . $package->id . '_' . $input['temp_name'];
        Helper::moveTempFile($key, '/temp-data/' . $input['temp_name']);

        return redirect()->route('app', ['id' => $input['app_id']]);

    }


}
