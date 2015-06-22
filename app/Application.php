<?php
namespace App;

# general
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

# models
use App\Comment;
use App\UserPass;

class Application extends Model {

    protected $table = 'application';

    public function user() {
        return $this->belongsToMany('App\UserPass', 'application_owner', 'app_id', 'owner_email');
    }

    public static function getUserAppsByEmail($email)
    {
        $app_list = DB::table('application_owner')->select('app_id')->where('owner_email', $email)->get();
        $applications = [];
        foreach ($app_list as $app) {
            $applications[] = DB::table('application')
                ->selectRaw('
                    application.*,
                    (SELECT COUNT(app_id) FROM app_install_user WHERE app_id = application.id) as install_user_count,
                    (SELECT package.created_at FROM package WHERE app_id = application.id ORDER BY package.created_at DESC LIMIT 1) as upload_time,
                    (SELECT last_installed FROM app_install_user WHERE app_id = application.id AND mail = "'.$email.'" LIMIT 1) as app_install_date,
                    (SELECT notify FROM app_install_user WHERE app_id = application.id AND mail = "'.$email.'" LIMIT 1) as notify_setting
                ')
                ->where('id', $app->app_id)->first();
        }
        return $applications;
    }

    public static function getInstalledAppsByEmail($email)
    {
        $app_list = DB::table('app_install_user')->select('app_id')->where('mail', $email)->get();
        $applications = [];
        foreach ($app_list as $app) {
            $applications[] = DB::table('application')
                ->selectRaw('
                    application.*,
                    (SELECT COUNT(app_id) FROM app_install_user WHERE app_id = application.id) as install_user_count,
                    (SELECT package.created_at FROM package WHERE app_id = application.id ORDER BY package.created_at DESC LIMIT 1) as upload_time,
                    (SELECT last_installed FROM app_install_user WHERE app_id = application.id AND mail = "'.$email.'" LIMIT 1) as app_install_date,
                    (SELECT notify FROM app_install_user WHERE app_id = application.id AND mail = "'.$email.'" LIMIT 1) as notify_setting
                ')
                ->where('id', $app->app_id)->first();
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

    public static function getAppDetails($app_id)
    {
        $app = DB::table('application')
            ->where('id', $app_id)
            ->first();

        return $app;
    }

    public static function getAppPackages($app_id)
    {
        $app = DB::table('package')
            ->where('app_id', $app_id)
            ->get();

        return $app;
    }


}
