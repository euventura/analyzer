<?php 

namespace App\Helpers;

class Math
{
    public static function DmstoDec($deg, $min, $sec = 0)
    {
        $transformer = 1;
        if(!$sec) {
            $sec = 0;
        }

        return ($deg+((($min*60)+($sec))/3600));
    }

    public static function DmsStringToDec($value)
    {

        $value = trim($value);
        $transformer = 1;
        $deg = substr($value,0, 2);

        $min = substr($value,3, 2);
        $sec = substr($value,5, 2);
        return self::DmsStringToDec()
    }
}