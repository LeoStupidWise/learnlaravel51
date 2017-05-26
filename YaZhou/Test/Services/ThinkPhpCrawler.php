<?php
/**
 * Created by PhpStorm.
 * User: Zoe
 * Date: 2017/3/22
 * Time: 15:38
 */

// 扒thinkphp5.0的手册
// 网址  http://www.jianshu.com/p/b5b119c0779c
// 把这个类里面的一些函数分离出来了，在crawler会有测试

class thinkphp
{
    // 保存目录
    private $savePath = '';
    // 远程连接路径
    private $listPath = '';
    // css保存路径
    private $cssPath  = '';
    // 初始化
    function __construct($dir, $link){
        $this->savePath = $dir;
        $this->cssPath     = $this->savePath . 'css/';
        if( ! file_exists( $this->savePath ) ){
            mkdir($this->savePath, '0777');
        }
        $this->listPath = substr($link, 0, strrpos($link, '/') + 1);
    }
    // run
    public function run(){
        $link     = 'http://www.kancloud.cn/manual/thinkphp5/118003';
        $html     = $this->getCode($link);
        $this->saveCSS($html);

        $arr     = $this->getDirLink($html);
        list($all, $link, $pid, $title) = $arr;
        $len     = count($link) - 1;
        $path     = $this->savePath;
        foreach($link as $k => $v){
            if( $pid[$k] == 0 ){
                $path = $this->savePath. iconv('UTF-8', 'gb2312//ignore', $title[$k] );
                if(!file_exists($path)){
                    mkdir($path,'0777');
                }
            }
            $html = $this->getCode($this->listPath.$v);
            $html = $this->removeCode($html, $title[$k]);

            if( $k != 0 ){
                $key = $k-1;
                $prevPage = $pid[$k] == 0 ? ('../' . $this->getPreveTitle($title, $pid, $key)) : '.';
                $prevPage .= '/'.basename($link[$key]);
                $html = $this->setPrevPage($html, $prevPage, $title[$key]);
            }
            if( $k != $len ){
                $key = $k+1;
                $nextPage = $pid[$key] == 0 ? '../' . $title[$key] : '.';
                $nextPage .= '/'.basename($link[$key]);
                $html = $this->setNextPage($html, $nextPage, $title[$key]);
            }
            $filename = $path.'/'.basename($link[$k]).'.html';
            file_put_contents($filename, iconv('UTF-8', 'gb2312//ignore', $html));
        }
    }
    // 获取上一个pid为0的title
    private function getPreveTitle($title, $pid, $k){
        for (; $k > 0; $k--) {
            if( $pid[$k] == 0 ){
                return $title[$k];
            }
        }
    }

    // 上一页
    private function setPrevPage($html, $prevPage, $title){
        return preg_replace('/<span class="jump-up">([\s\S]*)<a><\/a>/U','<span class="jump-up">\1<a href="'.$prevPage.'.html">'.$title.'</a>', $html);
    }

    // 下一页
    private function setNextPage($html, $nextPage, $title){
        return preg_replace('/<span class="jump-down">([\s\S]*)<a><\/a>/U','<span class="jump-down">\1<a href="'.$nextPage.'.html">'.$title.'</a>', $html);
    }

    // 删除与替换垃圾代码
    private function removeCode($html, $title){

        $replace = [
            // 替换标题
            '/<title>.+<\/title>/'                 => '<title>'.$title.'</title>',
            '/UTF-8/'                             => 'gb2312',
            // 替换css路径
            '/<link rel="stylesheet" href="\/\/static\.kancloud\.cn\/Static\/.*\/.*\/(.+)\.css\?v=\d+"/U' => '<link rel="stylesheet" href="../css/\1.css"',
            // 删除导航
            '/<div class="manual-head">[\s\S]*<div class="manual-right">/'                         => '<div class="manual-body"><div class="manual-right">',
            // 删除字体分享
            '/<div class="head\-tool">[\s\S]*<h1><\/h1>/U'                                        => '<h1>'.$title.'</h1>',
            // 删除低版本浏览器提示
            '/<\!\-\-\[if lte IE 8\]>[\s\S]*<\!\[endif\]\-\->/U'                                => '',
            // 删除JS代码替换为自己的JS方便统一管理代码
            '/<div class="think\-loading loading\-gear loading\-active">[\s\S]*<\/script>/'     => '<script src="./js/common.js"></script>',
            // 替换站内连接
            '/<a href="(\d+)">/'                                                                => '<a href="./\1.html">',
        ];
        $html = preg_replace(array_keys($replace), array_values($replace), $html);
        return $html;
    }

    // 保存样式
    private function saveCSS($html){
        preg_match_all('/<link rel="stylesheet" href="(.+)\?v=\d+">/U', $html, $match);
        if( isset($match[1]) ){
            if(!file_exists($this->cssPath)){
                mkdir($this->cssPath, '0777');
            }
            foreach($match[1] as $v){
                $css = $this->getCode('http:'.$v);
                file_put_contents($this->cssPath . basename($v), file_get_contents('http:'.$v));
            }
        }
    }

    // 获取目录连接
    private function getDirLink($html){
        preg_match_all('/<a href="\/manual\/thinkphp5\/(\d+)" data-pid="(\d+)" data-disable="0" data-id="\1">(.+)<\/a>/U', $html, $match);
        return $match;
    }

    // curl获取远程代码
    private function getCode($link){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $link);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, ' Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.2840.99 Safari/537.36');
        $html = curl_exec($ch);
        curl_close($ch);
        return $html;
    }
}

$class = new thinkphp('./chm/', 'http://www.kancloud.cn/manual/thinkphp5/118003');
$class->run();