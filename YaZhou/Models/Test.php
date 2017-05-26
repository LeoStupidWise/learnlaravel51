<?php
/**
 * Created by PhpStorm.
 * User: YaZhou
 * Date: 2017/1/20
 * Time: 17:45
 */

namespace Yz\Models;

use Illuminate\Database\Eloquent\Model;

class Test extends Model{
    protected   $table       =   "test";
    protected   $guarded     =   ["id"];
    public      $timestamps  =   false;
}