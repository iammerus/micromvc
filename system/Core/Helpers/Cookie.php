<?php
/**
 * Created by PhpStorm.
 * User: Melvin
 * Date: 25/9/2016
 * Time: 12:20 PM
 */

namespace MicroPos\Core\Helpers;


class Cookie
{
    public static function has($name)
    {
        return Arr::has($_COOKIE, $name);
    }

    public static function getAuthHash()
    {
        if(static::has('MicroPosAuthID')) {
            return $_COOKIE['MicroPosAuthID'];
        }
    }
}