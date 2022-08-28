<?php

namespace App\Helpers;

class Helper
{
    public static function objDecode($array)
    {
        return json_decode(json_encode($array));
    }
}