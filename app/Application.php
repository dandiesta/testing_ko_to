<?php
namespace App;

# general
use Aws\Laravel\AwsFacade;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

# models
use App\Comment;
use App\UserPass;

class Application extends Model {

    protected $table = 'application';

    protected $fillable = ['title', 'description', 'repository', 'api_key', 'icon_key', 'date_to_sort'];
    public function user() {
        return $this->belongsToMany('App\UserPass', 'application_owner', 'app_id', 'owner_email');
    }

    public static function createApp($data)
    {
        $params = [
            'title'         =>  $data['title'],
            'icon_key'      =>  $data['icon-selector'],
            'api_key'       =>  self::makeApiKey(),
            'description'   =>  $data['description'],
            'repository'    =>  $data['repository'],
            'date_to_sort'  =>  Carbon::today()
        ];

        $app = new Application($params);
        $app->save();

        if(!empty($app)) {
            DB::table('application_owner')->insert([
                'app_id'        =>  $app->id,
                'owner_email'    =>  Auth::user()->mail,
                'created_at'    =>  Carbon::today(),
                'updated_at'    =>  Carbon::today()
            ]);
        }

        return $app;
    }

    public static function makeApiKey()
    {
        do{
            $api_key = str_random();
        }while(static::selectByApiKey($api_key));
        return $api_key;
    }

    public static function selectByApiKey($key)
    {
        $api_key = DB::table('application')->where('api_key', $key)->first();

        return (empty($api_key)) ? false : true;
    }

    public static function getUserAppsByEmail($email)
    {
        $app_list = DB::table('application_owner as ao')
            ->join('application as app', 'ao.app_id', '=', 'app.id')
            ->select('app_id', 'app.*')->where('owner_email', $email)
            ->orderBy('updated_at', 'DESC')
            ->paginate(20);

        foreach($app_list as $app)
        {
            $app->user_count = UserPass::getCountUsersByApp($app->app_id);
            $app->latest_user_install = self::getLatestUserInstallDate($email, $app->app_id);
            $app->notify = UserPass::isAppNotify($app->app_id);
        }

        return $app_list;
    }

    public static function getInstalledAppsByEmail($email)
    {
        $app_list = DB::table('app_install_user as aiu')
            ->join('application as app', 'aiu.app_id', '=', 'app.id')
            ->select('app_id', 'app.*')->where('mail', $email)
            ->orderBy('app.updated_at', 'DESC')
            ->paginate(20);

        foreach ($app_list as $app) {
            $app->latest_user_install = self::getLatestUserInstallDate($email, $app->app_id);
            $app->notify = UserPass::isAppNotify($app->app_id);
        }

        return $app_list;
    }

    public static function getAppById($app_id)
    {
        return DB::table('application')->find($app_id);
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

    public static function checkUserOwnerByAppId($user_mail, $app_id)
    {
        $apps = DB::table('application_owner')
            ->where('owner_email', $user_mail)
            ->where('app_id', $app_id)
            ->get();
        return (count($apps) > 0);
    }

    public static function getInstallUserByAppId($app_id)
    {
        return DB::table('app_install_user')
            ->where('app_id', $app_id)
            ->get();
    }

    public static function getOwnersByAppId($app_id)
    {
        return DB::table('application_owner')
            ->where('app_id', $app_id)
            ->get();
    }

    public static function getTagsByAppId($app_id)
    {
        return DB::table('tag')
            ->where('app_id', $app_id)
            ->get();
    }

    public static function getActiveTagsByAppId($app_id)
    {
        $tags = DB::table('tag')
            ->select('id')
            ->where('app_id', $app_id)
            ->get();
        $ids = [];
        foreach ($tags as $tag) {
            $ids[] = $tag->id;
        }
        return $ids;
    }

    public static function getAppDetails($app_id)
    {
        $app = DB::table('application')
            ->where('id', $app_id)
            ->first();

        return $app;
    }

    public static function getAppPackages($app_id, $platform = 'all', $tags)
    {
        if ($platform == 'android') {
            $platform = ['Android'];
        } elseif ($platform == 'ios') {
            $platform = ['iOS'];
        } else {
            $platform = ['Android', 'iOS'];
        }

        if ($tags) {
            $package_ids = DB::table('package_tag')
                ->select('package_id')
                ->whereIn('tag_id', $tags)
                ->get();
            $ids = [];
            foreach ($package_ids as $pkg) {
                $ids[] = $pkg->package_id;
            }
            $packages = DB::table('package')
                ->where('app_id', $app_id)
                ->whereIn('platform', $platform)
                ->whereIn('id', $ids)
                ->orderBy('created_at', 'desc')
                ->paginate(20);
        } else {
            $packages = DB::table('package')
                ->where('app_id', $app_id)
                ->whereIn('platform', $platform)
                ->orderBy('created_at', 'desc')
                ->paginate(20);
        }
        foreach ($packages as $package) {
            $package->max_file_size = 50*1024*1024;//50 MB
            $package->is_file_size_warned = ($package->max_file_size < $package->file_size);
        }

        return $packages;
    }

    public function addNewOwner($email, $app_id)
    {
        return DB::table('application_owner')
            ->insert(['owner_email' => $email, 'app_id' => $app_id]);
    }
    public function deleteOwners($app_id)
    {
        return DB::table('application_owner')
            ->where('app_id', $app_id)
            ->whereNotIn('owner_email', [Auth::user()->mail])
            ->delete();
    }

}
