<?php

namespace App\Http\Controllers;

# general
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;

# models
use App\ApplicationOwner;
use App\UserPass;
use App\Application;
use App\Comment;
use App\Package;

class ApplicationController extends Controller
{
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

        $packages = Application::getAppPackages($app_id);
        foreach ($packages as $package) {
            $package->tags = Package::getTagsByPackageId($package->id);
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

    public function newApp()
    {
        return view('app.new', []);
    }

    public function preferences()
    {
        return view('app.preferences', []);
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

}
