<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class User extends Model
{
    public static function countUserPerPackage($id)
    {
        $count = DB::table('install_log')
            ->where('package_id', $id)
            ->count();

        return $count;
    }
}