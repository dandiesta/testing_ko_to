<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

use App\Comment;
use App\UserPass;

class Application extends Model {

    protected $table = 'application';

    public function user() {
        return $this->belongsToMany('App\UserPass', 'application_owner', 'app_id', 'owner_email');
    }

    public static function getUserAppsByEmail($email)
    {
        $app_list = DB::table('application_owner as ao')
            ->join('application as app', 'ao.app_id', '=', 'app.id')
            ->select('app_id', 'app.*')->where('owner_email', $email)
            ->orderBy('updated_at', 'DESC')
            ->get();

        $applications = [];
        foreach($app_list as $app)
        {
            $app->user_count = UserPass::getCountUsersByApp($app->app_id);
            $app->latest_user_install = self::getLatestUserInstallDate($email, $app->app_id);
            $app->notify = UserPass::isAppNotify($app->app_id);
            $applications[] = $app;
        }

        return $applications;
    }

    public static function getInstalledAppsByEmail($email)
    {
        $app_list = DB::table('app_install_user as aiu')
            ->join('application as app', 'aiu.app_id', '=', 'app.id')
            ->select('app_id', 'app.*')->where('mail', $email)
            ->orderBy('app.updated_at', 'DESC')
            ->get();

        $applications = [];
        foreach ($app_list as $app) {
            $app->latest_user_install = self::getLatestUserInstallDate($email, $app->app_id);
            $app->notify = UserPass::isAppNotify($app->app_id);
            $applications[] = $app;
        }

        return $applications;
    }

    public static function getAppById($id) {

    }

    public static function getAllApps()
    {
        return DB::table('application')->get();
    }

    public static function getTopApps($email, $limit)
    {
        $applications = DB::table('application')
            ->orderBy('created_at', 'DESC')
            ->take($limit)
            ->get();

        foreach($applications as $app)
        {
            $app->comment_count = Comment::getCountByApplication($app->id);
            $app->user_count = UserPass::getCountUsersByApp($app->id);
            $app->latest_user_install = self::getLatestUserInstallDate($email, $app->id);
        }

        return new Collection($applications);
    }

    public static function getLatestUserInstallDate($user_mail, $app_id)
    {
        $app = DB::table('app_install_user')
            ->where('mail', $user_mail)
            ->where('app_id', $app_id)
            ->first();

        return ($app) ? $app->last_installed : null;
    }

}
