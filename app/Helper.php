<?php
namespace App;

use Aws\Laravel\AwsFacade;
use Aws\S3\S3Client;

class Helper
{
    public function getArrayable($arr)
    {
        $new_arr = [];
        foreach ($arr as $key => $val) {
            $new_arr[$val->id] = $val->name;
        }

        return $new_arr;
    }

    public static function isIOSmobile()
    {
        $iPod    = stripos($_SERVER['HTTP_USER_AGENT'],"iPod");
        $iPhone  = stripos($_SERVER['HTTP_USER_AGENT'],"iPhone");
        $iPad    = stripos($_SERVER['HTTP_USER_AGENT'],"iPad");
        return ($iPod || $iPhone || $iPad);
    }

    /**
     * @param $file string complete file path
     * @param $key string file path on aws
     */
    public static function uploadFile($file, $key)
    {
        $s3 = S3Client::factory(array(
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
        ));
        $body = fopen($file, 'rb');
        $s3->upload(env('AWS_S3_BUCKET'), $key, $body);
    }

    public static function randomString($length)
    {
        $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $size = strlen($chars) - 1;
        $string = '';
        for ($i = 0; $i < $length; $i++) {
            $string .= $chars[rand(0, $size)];
        }
        return $string;
    }

    public static function jsonResponse($status,$contents)
    {
        $header = array(
            $status,
            'Content-type: application/json',
        );
        return array($header,json_encode($contents));
    }

    public static function moveTempFile($key, $source)
    {
        $s3 = AwsFacade::get('s3');

        $s3->copyObject(array(
            'Bucket' => env('AWS_S3_BUCKET'),
            'Key' => $key,
            'CopySource' => env('AWS_S3_BUCKET') . $source,
        ));

        $s3->deleteObject(array(
            'Bucket'       => env('AWS_S3_BUCKET'),
            'Key'          => $source,
        ));;
    }
}