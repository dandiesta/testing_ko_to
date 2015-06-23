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
}