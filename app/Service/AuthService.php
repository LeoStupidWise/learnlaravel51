<?php
/**
 * Created by PhpStorm.
 * User: Zoe
 * Date: 2017/5/26
 * Time: 11:58
 */

namespace App\Service;

class AuthService
{
    public function pwdEncrypt($pwd)
    {
        // 密码加密，比如注册时传递了明文密码，这里进行加密处理，然后保存到数据库
        $new_pwd    =  md5($pwd);
        return $new_pwd;
    }
}