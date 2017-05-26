<?php
/**
 * Created by PhpStorm.
 * User: Zoe
 * Date: 2017/3/23
 * Time: 11:00
 */

namespace Yz\Test\Services;

class File{
    private $address = "D:\\YaZhou\\Projects\\laravel-test\\public\\curl";

    public function setAddress($address)
    {
        $this->address    =  $address;
    }

    public function newFile($content=null, $name=null, $ext="txt", $address=null)
    {
        // 新建一个文件
        // $content    内容
        // $address    地址，默认地址为$this->address
        // $name       文件名，默认文件名为当前时间
        // $ext        扩展名，默认扩展名为txt
        if (!$address) {
            $address         = $this->address;
        }
        if (!$name) {
            $name            = date("Y-m-d-H-i-s").".".$ext;
        } else {
            // 如果标题中含有斜杠（不管正反），那么就不能建立以这个标题为名字的文件
            $name            = str_replace("\\", "-s-", $name);
            $name            = str_replace("/", "-a-", $name);
            $name            = $name.".".$ext;
        }
        $address             = $address."\\".$name;
//        $address             = iconv("UTF-8", "GBK", $address);
        // windows下中文编码为GBK，如果文件名有中文，必须转换成GBK，如果传进来的变量已经是GBK，这里再转的话就会出错
        echo $address."<br/>";
        $handle              = fopen($address, 'w') or die("Unable to open file");
        // TODO 写文件之前应该先判断有没有已存在同名文件，可以将这些文件名保存起来
        fwrite($handle, $content);
        fclose($handle);
    }
}