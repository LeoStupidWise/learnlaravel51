<?php
/**
 * Created by PhpStorm.
 * User: Zoe
 * Date: 2017/5/26
 * Time: 12:18
 */

namespace App\Service\Common;

class CommonService
{
    public function jsAlert($msg)
    {
        $str    =  "<script>alert('$msg')</script>";
        return $str;
    }
}