<?php
// app/Helpers/HashHelper.php

namespace App\Helpers;

class HashHelper
{
    public static function encode($id)
    {
        return strtr(base64_encode($id), '+/=', '._-');
    }

    public static function decode($hash)
    {
        return base64_decode(strtr($hash, '._-', '+/='));
    }
}
