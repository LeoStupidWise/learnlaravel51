<?php
/**
 * Created by PhpStorm.
 * User: YaZhou
 * Date: 2017/1/20
 * Time: 17:55
 */

namespace Yz\Test\Controllers;

use App\Http\Controllers\Controller;
use Yz\Test\Models\Test;

class DatabaseController extends Controller{
    public function connect()
    {
        $builder        =  Test::where("name", "like", "z")
            ->paginate();
    }
}