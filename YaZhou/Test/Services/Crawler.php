<?php
/**
 * Created by PhpStorm.
 * User: Zoe
 * Date: 2017/3/22
 * Time: 15:40
 */

namespace Yz\Test\Services;

use Yz\Test\Services\Curl;
use Yz\Test\Services\File;

class Crawler{

    private $link        =  "";          // 入口链接，从这个链接开始扒
    private $floor       =  null;       // 程序爬的层数，入口处为第一层
    private $dir         =  "D:\\YaZhou\\Projects\\laravel-test\\public\\crawler";
    private $follower   =  [];          // 入口页面中的所有超链接

    public function __construct($link, $floor)
    {
        $this->link       =  $link;            // 爬虫初始链接
        $this->floor      =  $floor;           // 爬取的层数
    }

    public function setLink($link)
    {
        $this->link       =  $link;
    }

    public function setFloor($floor)
    {
        $this->floor      =  $floor;
    }

    public function getCodeTp($link)
    {
        // 从ThinkPhpCrawler中取出
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $link);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, ' Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.2840.99 Safari/537.36');
        $html = curl_exec($ch);
        curl_close($ch);
        return $html;
    }

    public function start()
    {
        // 开扒
        set_time_limit(0);
        $curl            =  new Curl();
        $html_data       =  $curl->getPageContent($this->link);
        $title           =  iconv("UTF-8", "GBK", $curl->getHtmlTitle($html_data));
        $path            =  $this->dir . "\\" . $title;
        // 建立页面文件夹
        if (is_dir($path)) {
            return false;
        }
        mkdir($path);
        // 添加README
        $links_org       =  $curl->getAllLink($html_data);
        $links_single    =  $curl->turnSingle($links_org);
        $followers       =  $curl->separateByExt($links_single);
        $this->addReadMe($path, $this->link, array_merge($followers["sites"], $followers["pics"]));
        $file            =  new File();
        $file->newFile($html_data, $title, "html", $path);
        // 添加followers文件夹
        $path_followers  =  $path . "\\" . "followers";
        if (!is_dir($path_followers)) {
            mkdir($path_followers);
        }
        // 为followers文件夹增加内容
        if (count($followers["sites"]) > 0) {
            $count       =  1;
            foreach ($followers["sites"] as $keyFir=>$valFir) {
                $html_data_flw    =  $curl->getPageContent($valFir);
                $title_flw        =  iconv("UTF-8", "GBK", $count."--".$curl->getHtmlTitle($html_data_flw));
                $file->newFile($html_data_flw, $title_flw, "html", $path_followers);
                $count++;
            }
        }
    }

    private function addReadMe($dir, $link, $followers)
    {
        // 为文件夹添加一个readMe文件，说明这个文件夹是截取的哪个网站，且当前页面含有哪些链接
        // $link：string，当前网页的地址
        // $followers：array，当前页面包含的链接
        $content        =  $link;
        if (count($followers) > 0) {
            foreach ($followers as $keyFir=>$valFir) {
                $content  .=  "\r\n" . $valFir;
            }
        }
        $handler        =  fopen($dir."\\"."README.txt", "w");
        $write_ok       =  fwrite($handler, $content);
        // fwrite返回的是写入文件的字符数，如果失败则返回FALSE
        fclose($handler);
        return $write_ok;
    }

}