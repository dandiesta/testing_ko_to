<?php
namespace App;

# general
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Aws\S3\S3Client;
use Illuminate\Support\Facades\Auth;

class Package extends Model
{
    protected $table = 'package';

    protected $fillable = [
        'app_id',
        'platform',
        'file_name',
        'title',
        'description',
        'ios_identifier',
        'original_filename',
        'file_size'
    ];
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

    public static function getById($package_id)
    {
        return DB::table('package')
            ->where('id', $package_id)
            ->first();

    }

    public static function getInstalledByEmail($email)
    {
        $package_ids = DB::table('install_log')
            ->where('mail', $email)
            ->groupBy('package_id')
            ->get();

        $packages = [];
        foreach ($package_ids as $package) {
            $packages[] = self::getById($package->package_id);
        }
        return $packages;
    }

    public function getInstallUrlAttribute()
    {
        $client = S3Client::factory(array(
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
        ));
        $url = $client->getObjectUrl(env('AWS_S3_BUCKET'),"package/{$this->app_id}/{$this->id}_{$this->file_name}",'+60 min');
        return $url;
    }

    public static function isInstalled($id)
    {
        return DB::table('install_log')
            ->where('package_id', $id)
            ->where('mail', Auth::user()->mail)
            ->first();
    }

    public static function lastDateInstalled($id)
    {
        return DB::table('install_log')
            ->select('installed')
            ->where('package_id', $id)
            ->where('mail', Auth::user()->mail)
            ->orderBy('installed', 'desc')
            ->first();
    }
    public static function installedUsers($id)
    {
        return DB::table('install_log')
            ->where('package_id', $id)
            ->groupBy('mail')
            ->get();
    }
}