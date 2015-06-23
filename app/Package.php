<?php
namespace App;

# general
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class Package extends Model
{
    protected $table = 'package';

    protected $tags = null;

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

    public function getTags()
    {
        if($this->tags===null){
            $this->tags = TagDb::selectByPackageId($this->getId());
        }
        return $this->tags;
    }
    
    public static function deleteById($id)
    {
        DB::table('package')
            ->where('id', $id)
            ->delete();
    }

    public static function getCommentedByIds($ids)
    {
        $in = [];
        foreach ($ids as $id) {
            $in[] = $id->package_id;
        }
        $raw_package = DB::table('package')
            ->whereIn('id', $in)
            ->get();

        $packages = [];
        foreach ($raw_package as $package) {
            $packages[$package->id] = $package;
        }
        return $packages;
    }

    public static function getTagsByPackageId($package_id)
    {
        return DB::table('package_tag')
            ->selectRaw('
            package_tag.*,
            (SELECT name FROM tag WHERE tag.id = package_tag.tag_id) as name
            ')
            ->where('package_id', $package_id)
            ->get();
    }
}