<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Application extends Model {

    protected $table = 'application';

    public function user() {
        return $this->belongsToMany('App\UserPass', 'application_owner', 'app_id', 'owner_email');
    }

    public static function getUserAppsById($email)
    {
        $app_list = DB::table('application_owner')->select('app_id')->where('owner_email', $email)->get();
        $applications = [];
        foreach ($app_list as $app) {
            $applications[] = DB::table('application')
                ->selectRaw('
                    application.*,
                    (SELECT COUNT(app_id) FROM app_install_user WHERE app_id = application.id) as install_user_count,
                    (SELECT package.created_at FROM package WHERE app_id = application.id ORDER BY package.created_at DESC LIMIT 1) as upload_time,
                    (SELECT last_installed FROM app_install_user WHERE app_id = application.id AND mail = "'.$email.'" LIMIT 1) as app_install_date
                ')
                ->where('id', $app->app_id)->first();
        }
        return $applications;
    }

}
