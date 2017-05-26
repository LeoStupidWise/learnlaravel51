<?php
/**
 * Created by PhpStorm.
 * User: Zoe
 * Date: 2017/3/22
 * Time: 11:54
 */
namespace Yz\Test\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Yz\Test\Models\Address;
use Yz\Test\Services\Crawler;
use Yz\Test\Services\File;
use Yz\Test\Controllers\ClassTest;
use Yz\Models\Test;

class TestClass {
    // 测试用class，与laravel无关
    const FOO         =  "BAR";
    const BAR         =  "FOO";
    public $property_pub      =  1;
    private $property_prv     =  2;
    protected $property_prt   =  3;

    public function fun_pub()
    {
        echo "I am a public function";
        // public function
    }

    static function fun_stc()
    {
        echo "I am a static function";
    }
}

class TestController extends Controller{

    public $arr = [
//        [/
//            "name"    =>  "Alice",
//            "gender"  =>  "Female",
//            "age"     =>  17
//        ],
//        [
            "name"    =>  "Bob",
            "gender"  =>  "Male",
            "age"     =>  20
//        ]
    ];

    public function test()
    {
        //
//        $path        =  __DIR__;
//        $real_path   =  realpath($path);
//        dump($path);
//        dump($real_path);
//        $file_svc    =  new File();
//        $file_svc->newFile("This is a test file");

//     echo date("Ymd", strtotime("+1 month"));
//        $sum        =  with(array_sum(range(0, 100)));
//        dump($sum);
        $record    =  Test::find(1);
        dump($record);

//        $testClass    =  new TestClass();
//        $testClass->fun_pub();

//        return view("test::incorrect-key", [
//            "responseCode"    =>  200
//        ]);
    }

    public function crawler()
    {
        // 爬虫测试
        $link           =  "http://www.discuz.net/forum.php";
        $crawler        =  new Crawler();
        $html           =  $crawler->getCodeTp($link);
        dump($html);
    }

    public function amzIncorrect($res_code)
    {
        return view("test::incorrect-key",[
            "responseCode"    =>  (int)$res_code
        ]);
    }

    public function amzHealthPage()
    {
        $services         =  [
            [
                "name"      =>  "SR",
                "massage"   =>  "disabled"
            ],
            [
                "name"      =>  "SNK",
                "massage"   =>  "success"
            ],
            [
                "name"      =>  "SPE",
                "massage"   =>  "other"
            ]
        ];
        $migrations       =  [
            [
                "name"      =>  "Customer",
                "code"      =>  1
            ],
            [
                "name"      =>  "Seller",
                "code"      =>  0
            ],
            [
                "name"      =>  "Walker",
                "code"      =>  2,
            ]
        ];
        return view("test::health-page",[
            "services"    =>  $services,
            "migrations"  =>  $migrations
        ]);
    }

    public function authLogin()
    {
        return view("test::auth.login");
    }
}