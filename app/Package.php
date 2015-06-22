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
}