<?php
namespace App;

# general
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Tag extends Model
{
    protected $table = 'tag';

    protected $fillable = ['app_id', 'name'];

    public static function selectByPackageId($id)
    {
        $tags = DB::table('package_tag as p')
            ->join('tag as t', 't.id', '=', 'p.tag_id')
            ->select('t.*')
            ->where('p.package_id', $id)
            ->get();

        return $tags;
    }

    public static function getAll($app_id)
    {
        $tags = Tag::where('app_id', $app_id)->get();

        return $tags;
    }


}