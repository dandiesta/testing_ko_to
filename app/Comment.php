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
}
