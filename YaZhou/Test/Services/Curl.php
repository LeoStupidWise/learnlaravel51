<?php
/**
 * Created by PhpStorm.
 * User: Zoe
 * Date: 2017/3/22
 * Time: 15:52
 */

namespace Yz\Test\Services;

class Curl{

    private $ext_site   = ["com", "net", "cn", "org", "html"];
    private $ext_pic    = ["png", "gif", "jpg", "bmp", "jpeg"];

    public function getPageContent($link)
    {
        $curl        =  curl_init();
        curl_setopt($curl, CURLOPT_URL, $link);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $data        =  curl_exec($curl);
//        $data        =  str_replace("\n", "<br/>", $data);
        $data        =  str_replace("\n", "", $data);
        return $data;
    }

    public function getWeather($city)
    {
        // 获取城市的天气
        $data          = "theCityName=$city";
        $curl          = curl_init();
        curl_setopt($curl, CURLOPT_URL, "http://www.webxml.com.cn/WebServices/WeatherWebService.asmx/getWeatherbyCityName");
        curl_setopt($curl, CURLOPT_HEADER, 0);           // 不显示 Header
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);  // 只是下载页面内容，不直接打印
        curl_setopt($curl, CURLOPT_POST, 1);             // 此请求为 post 请求
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);  // 传递 post 参数
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            "application/x-www-form-urlencoded;charset=utf-8",
            "Content-length: ".strlen($data)
        )); // 设置 HTTP Header
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.152 Safari/537.36'); // 伪造一个 HTTP_USER_AGENT 信息，解决为将对象引用设置到对象的实例问题
        $rtn = curl_exec($curl);

        if(!curl_errno($curl)) {
            // $info = curl_getinfo($curlobj);
            // print_r($info);
            echo $rtn;
        } else {
            echo 'Curl error: ' . curl_error($curl);
        }
    }

    public function getHtmlTitle($data)
    {
        // 获取在一个html中<title>内容
        $start        = strpos($data, "<title>");
        $end          = strpos($data, "</title>");
        $title        = substr($data, $start, $end-$start);
        $title        = str_replace("<title>", "", $title);
        $title        = str_replace(" ", "", $title);
        return $title;
    }

    public function getAllLink($html_data)
    {
        // 获取一个html中的链接
        $result           = [];
        // (?!['"])http[s]?:\/\/[\S]*(?=['"])
        // 原始的正则如上所述，在最外面加一堆括号的原因详见：http://www.jb51.net/article/22304.htm
        $preg             = '{(?![\'"])http[s]?:\/\/[\S]*(?=[\'"])}';
        // TODO 这种正则匹配方式只能扒到显示的链接即在页面的表现形式为“http(s):....”
        // TODO 如果链接是相对于本页面的，比如href="/php/php_secure_mail.asp"（参见网页http://www.w3school.com.cn/php/php_mysql_select.asp），那么就扒不到了
        preg_match_all($preg, $html_data, $result);
        return $result[0];
    }

    // TODO 获取链接之后，需要去除重复链接和空白链接，分离网页链接、图片链接和其他链接
    // TODO 分离不同类型的URL，现阶段先通过后缀名来判断，像http://0.gravatar.com/avatar/39ad5fe7c62a936c217bd7b40cc3428b?s=32&#038;d=retro&#038;r=g这种URL
    // TODO 它现在是一个图片，但通过这个URL可以得到各种东西，以后可能是其他东西。有一个办法可以判断这个URL是什么，那就是对这个URL请求一次
    public function turnSingle($links)
    {
        // 去除空白、重复链接
        $result        =  [];
        $link_tmp      =  [1];
        // 去除空白链接
        if (count($links) > 0) {
            foreach($links as $keyFir=>$valFir) {
                $valFir_arr    = explode("//", $valFir);
                // 去除空白链接
                if (isset($valFir_arr[1]) && $valFir_arr[1] !="") {
                    // 去除重复
                    $same      =  false;
                    foreach ($link_tmp as $item=>$value) {
                        if ($valFir_arr[1] == $value) {
                            $same    =  true;
                        }
                    }
                    if (!$same) {
                        $result[]    =  $valFir;
                        $link_tmp[]  =  $valFir_arr[1];
                    }
                }
            }
        }
        return $result;
    }

    public function separateByExt($links)
    {
        // 通过后缀来分离不同URL，没有后缀的暂不做处理，返回所有网页
        // http://t.qq.com/gb_vip，像这种，是一个人的主页，也有可能是一张图片，要看这个URL返回的是什么啦
        $result        =  [
            "sites"   =>  [],
            "pics"    =>  [],
        ];
        if (count($links) > 0) {
            foreach ($links as $keyFir=>$valFir) {
                $ext           =  strtolower(substr($valFir, strrpos($valFir, ".") + 1));
                if (in_array($ext, $this->ext_site)) {
                    $result["sites"][]  =  $valFir;
                }
                if (in_array($ext, $this->ext_pic)) {
                    $result["pics"][]   =  $valFir;
                }
            }
        }
//        dump($result);
        return $result;
    }

}