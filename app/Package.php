<?php
namespace App;

# general
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class Package extends Model
{
    protected $table = 'package';

    protected $fillable = ['title', 'description'];
    protected $tags = null;
    public $timestamps = false;

    public static function selectByPackageId($id)
    {
        $app = DB::table('package as p')
            ->join('application as a', 'a.id', '=', 'p.app_id')
            ->select('a.title as app_title', 'a.id as app_id', 'a.*', 'p.*')
            ->where('p.id', $id)
            ->first();

        return $app;
    }

    public static function isFileSizeWarned($size)
    {
        $size = floor(($size/1024)/1024);

        if ($size <= 0) {
            return false;
        }

        return $size;
    }

    public static function getDetails($id)
    {
        $details = DB::table('package')
            ->where('id', $id)
            ->first();
        return $details;
    }

    public static function addNewTag($tag_id, $package_id)
    {
        DB::table('package_tag')
            ->insert([
                'package_id' => $package_id,
                'tag_id' => $tag_id
            ]);
    }

    public static function removeAllTags($package_id)
    {
        DB::table('package_tag')->where('package_id', $package_id)->delete();
    }
}