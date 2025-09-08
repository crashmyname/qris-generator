<?php
namespace Helpers;

class Hash {

    public static function make($data)
    {
        $encrypt = password_hash($data, PASSWORD_BCRYPT);
        return $encrypt;
    }

    public static function verify($data,$params)
    {
        $decrypt = password_verify($data,$params);
        return $decrypt;
    }

}