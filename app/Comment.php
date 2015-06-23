<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Comment extends Model {

    protected $table = 'comment';

    public function application() {
        return $this->belongsTo('App\Application', 'app_id', 'id');
    }

    public static function getCountByApplication($app_id)
    {
        $count = DB::table('comment')
            ->where('app_id', $app_id)
            ->count();

        return $count;
    }

    public static function getTopByApplicationId($app_id, $limit = 2)
    {
        return DB::table('comment')
            ->where('app_id', $app_id)
            ->orderBy('number', 'desc')
            ->paginate($limit);
    }

    public static function getPackageIdsByApplicationId($app_id)
    {
        return DB::table('comment')
            ->selectRaw('DISTINCT package_id')
            ->where('app_id', $app_id)
            ->get();
    }
}
