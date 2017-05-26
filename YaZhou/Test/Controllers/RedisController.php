<?php
/**
 * Created by PhpStorm.
 * User: Zoe
 * Date: 2017/3/27
 * Time: 9:45
 */

namespace Yz\Test\Controllers;

use App\Http\Controllers\Controller;

class RedisController extends Controller{
    public function concurrent()
    {
        // 高并发测试
        $redis        =  new \Redis();
        $redis->connect("192.168.170.128");
        for ($i = 0; $i < 100000; $i++) {
            $count    =  $redis->get("count");
            $count    =  $count + 1;
            $redis->set("count", $count);
        }
        echo "This is OK";
    }
}