<?php
namespace App;

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
}