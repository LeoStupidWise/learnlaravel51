<?php
/**
 * Created by PhpStorm.
 * User: Zoe
 * Date: 2017/3/22
 * Time: 15:59
 */

namespace Yz\Test\Controllers;

use App\Http\Controllers\Controller;
use Yz\Test\Services\Crawler;
use Yz\Test\Services\Curl;
use Yz\Test\Services\File;

class CurlController extends Controller{
    public function basic()
    {
        $link          =  "http://laravelacademy.org/post/3910.html";
        $link          =  "http://t.qq.com/gb_vip";
//        $link          =  "http://t.qq.com/logout.php";
//        $link          =  "http://laravelacademy.org/post/3910.html";
        $dir           =  "D:\\YaZhou\\Projects\\laravel-test\\public\\crawler\\4.html";
        $content       =  file_get_contents($dir);
        dump($content);

//        $curl          =  new Curl();
//        $file          =  new File();
//        $data          =  $curl->getPageContent($link);
//        $title         =  $curl->getHtmlTitle($data);
//        $links_org     =  $curl->getAllLink($data);
//        $links_single  =  $curl->turnSingle($links_org);
//        $curl->separateByExt($links_single);
//        dump($links_org);
//        dump($data);
//        dump($title);
//        dump($links_single);
//        $file->newFile($data, $title, "html");
    }

    public function getWeather()
    {
        $city          =  "北京";
        $curl          =  new Curl();
        $curl->getWeather($city);
    }

    public function crawler()
    {
        $link          =  "http://laravelacademy.org/post/3910.html";
        $crawler       =  new Crawler($link, 5);
        $crawler->start();
    }
}