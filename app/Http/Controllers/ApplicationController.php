<?php

namespace App\Http\Controllers;

# general
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Aws\Laravel\AwsFacade;

# models
use App\ApplicationOwner;
use App\UserPass;
use App\Application;
use App\Comment;
use App\Package;
use App\Tag;

class ApplicationController extends Controller
{
    protected $rules = [
        'title'         =>  'required|max:255',
        'description'   =>  'required',
        'repository'    =>  array('required', 'regex:/(https:\/\/github.com\/)([a-zA-Z0-9_-]+)(\/)([a-zA-Z0-9_-]+)/')
    ];

    protected $custom_messages = [
        'title.max'             =>  'Title should not exceed 255 characters.',
        'title.required'        =>  'Application Title is required.',
        'description.required'  =>  'Description for the Application is required.',
        'repository.required'   =>  'Repository link is required',
        'repository.regex'      =>  'Invalid Git repository url'
    ];

    public function package()
    {
        return view('pages.packages.index');
    }

    public function index() {
        $input = Request::all();
        $app_id = $input['id'];
        $pf = Request::input('pf', 'all');
        $filter_open = Request::input('filter_open', 0);
        $current_page = Request::input('current_page ', 1);
        $tags = explode(' ', Request::input('tags'));
        $is_file_size_warned = false;

        $input['page'] = $current_page + 1;
        $next_page_url = route('app', $input);
        $input['page'] = $current_page - 2;
        $prev_page_url = route('app', $input);

        $app = Application::getAppById($app_id);
        $comment_count = Comment::getCountByApplication($app_id);
        $top_comments = Comment::getTopByApplicationId($app_id);
        $commented_package = Package::getCommentedByIds(Comment::getPackageIdsByApplicationId($app_id));

        $app->install_user_count = UserPass::getCountUsersByApp($app_id);
        $app->latest_user_install = Application::getLatestUserInstallDate(Auth::user()->mail, $app_id);
        $app->is_owner = Application::checkUserOwnerByAppId(Auth::user()->mail, $app_id);
        $app->install_user = Application::getInstallUserByAppId($app_id);
        $app->owners = Application::getOwnersByAppId($app_id);
        $app->tags = Application::getTagsByAppId($app_id);
        $active_tags = Application::getActiveTagsByAppId($app_id);

        $packages = Application::getAppPackages($app_id, $pf, $tags);
        foreach ($packages as $package) {
            $package->tags = Package::getTagsByPackageId($package->id);
            $package->is_installed = Package::isInstalled($package->id);
        }
        $next_page_url = count($packages) > 20 ? $next_page_url : null;

        $data = [
            'app' => $app,
            'pf' => $pf,
            'comment_count' => $comment_count,
            'top_comments' => $top_comments,
            'commented_package' => $commented_package,
            'action' => 'app',
            'filter_open' => $filter_open,
            'packages' => $packages,
            'current_page' => $current_page,
            'next_page_url' => $next_page_url,
            'prev_page_url' => $prev_page_url,
            'is_file_size_warned' => $is_file_size_warned,
            'active_tags' => $active_tags,
        ];
        return view('app.index', $data);
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

    public function comment()
    {
        $app_id = Request::input('id', null);
        if (!$app_id) {
            return redirect()->route('top_apps');
        }
        $app = Application::getAppById($app_id);
        $app->install_user_count = UserPass::getCountUsersByApp($app_id);
        $app->latest_user_install = Application::getLatestUserInstallDate(Auth::user()->mail, $app_id);
        $app->is_owner = Application::checkUserOwnerByAppId(Auth::user()->mail, $app_id);
        $app->install_user = Application::getInstallUserByAppId($app_id);
        $app->owners = Application::getOwnersByAppId($app_id);
        $app->tags = Application::getTagsByAppId($app_id);

        $install_packages = Package::getInstalledByEmail(Auth::user()->mail);

        $page = Request::input('page', 1);
        $comment_count = Comment::getCountByApplication($app_id);
        $top_comments = Comment::getTopByApplicationId($app_id, 20, ($page-1)*20);
        $top_comments->setPath('custom/url');
        $commented_package = Package::getCommentedByIds(Comment::getPackageIdsByApplicationId($app_id));

        $data = [
            'app' => $app,
            'install_packages' => $install_packages,
            'action' => 'comment',
            'comment_count' => $comment_count,
            'top_comments' => $top_comments,
            'commented_package' => $commented_package,

        ];
        return view('app.comment', $data);
    }

    public function postComment()
    {
        $inputs = Request::all();
        $comment_count = Comment::getCountByApplication($inputs['id']);
        $mail = Auth::user()->mail;



        $comment = new Comment();
        $comment->app_id = $inputs['id'];
        $comment->package_id = $inputs['package_id'];
        $comment->message = $inputs['message'];
        $comment->number = $comment_count + 1;
        $comment->mail = $mail;
        $comment->save();

        return redirect()->route('comment_app', ['id'=>$inputs['id']]);
    }

    public function createApp()
    {
        $input = Input::all();
        $validator = Validator::make($input, $this->rules, $this->custom_messages);

        if($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($input);
        }

        $input['icon_name'] = str_random(10) . "." . $input['icon-selector']->getClientOriginalExtension();
        $app = Application::createApp($input);

        //Process image
        if($input['icon-selector']->isValid()) {
            $icon = $input['icon_name'];
            $input['icon-selector']->move(public_path() . '/uploads/', $icon);
            $s3 = AwsFacade::get('s3');
            $s3->putObject(array(
                'Bucket'        => env('AWS_S3_BUCKET'),
                'Key'           => '/app-icons/' . $app->id . '/' . $icon,
                'ACL'           => 'public-read',
                'SourceFile'    => public_path() . '/uploads/' . $icon
            ));

            $app->icon_key = 'app-icons/' . $app->id . '/' . $icon;
            $app->save();

        } else {
            return redirect()->back()->withInput();
        }

        return redirect()->route('app', ['id' => $app->id]);
    }

    public function preferences()
    {
        $app_id = Request::input('id');

        $app = Application::find($app_id);
        $app->owners = Application::getOwnersByAppId($app_id);
        $app->is_owner = Application::checkUserOwnerByAppId(Auth::user()->mail, $app->id);
        $app->install_user = Application::getInstallUserByAppId($app_id);
        $app->all_tags = Tag::getAll($app->id);

        $data = [
            'app' => $app,
            'action' => 'preferences'
        ];

        return view('app.preferences', $data);
    }

    public function documentation($page = 'api')
    {
        switch ($page)
        {
            case 'api_upload':
                return view('docs.upload');
                break;
            case 'api_package_list':
                return view('docs.package_list');
                break;
            case 'api_delete':
                return view('docs.delete');
                break;
            case 'api_create_token':
                return view('docs.create_token');
                break;
            default:
                return view('docs.api');
                break;
        }
    }

    public function updatePreferences()
    {
        $input = Input::all();

        $validator = Validator::make($input, $this->rules, $this->custom_messages);

        if($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($input);
        }

        //icon is not yet included
        $app = Application::find($input['id']);

        if (isset($input['icon-selector'])) {
            $input['icon_name'] = str_random(10) . "." . $input['icon-selector']->getClientOriginalExtension();

            if($input['icon-selector']->isValid()) {
                $icon = $input['icon_name'];
                $input['icon-selector']->move(public_path() . '/uploads/', $icon);
                $s3 = AwsFacade::get('s3');
                $s3->deleteObject(array(
                    'Bucket'       => env('AWS_S3_BUCKET'),
                    'Key'          => '/' . $app->icon_key,
                ));
                $s3->putObject(array(
                    'Bucket'        => env('AWS_S3_BUCKET'),
                    'Key'           => '/app-icons/' . $input['id'] . '/' . $icon,
                    'ACL'           => 'public-read',
                    'SourceFile'    => public_path() . '/uploads/' . $icon
                ));

                $app->icon_key = 'app-icons/' . $input['id'] . '/' . $icon;

            }
        }

        $app->title = $input['title'];
        $app->description = $input['description'];
        $app->repository = $input['repository'];
        $app->save();

        return redirect(route('preferences', ['id' => $app->id]) . '#edit-info');
    }

    public function deleteTagsPreferences()
    {
        $input = Request::all();

        if (!isset($input['tags'])) {
            return redirect(route('preferences', ['id' => $input['id']]) . '#delete-tags');
        }

        foreach ($input['tags'] as $tag) {
            Tag::deleteFromPackage($tag);
            $t = Tag::find($tag);
            $t->delete();
        }

        return redirect(route('preferences', ['id' => $input['id']]) . '#delete-tags');
    }

    public function updateOwnersPreferences(Application $app)
    {
        $input = Request::all();
        $id = $input['id'];

        $app->deleteOwners($id);

        foreach ($input['owners'] as $owner) {
            $owner = trim($owner);
            if ($owner) {
                $app->addNewOwner($owner, $id);
                continue;
            }
        }

        return redirect(route('preferences', ['id' => $id]) . '#owners');
    }

    public function updateAPI()
    {
        $app = Application::find(Request::input('id'));
        $app->api_key = Application::makeApiKey();
        $app->save();

        return redirect()->route('preferences', ['id' => $app->id]);

    }
}
