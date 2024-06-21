<?php

namespace App\Traits\Common;
use Illuminate\Http\Request;

trait CommonTrait{
    protected static array $_time_arr = [];
    public static function formTimeArr($from = 0, $to = 23){
        for($i=$from; $i<= $to; $i++){
            for($j=0; $j<60; $j+=15){
                $time = (($i < 10) ? ('0'.$i) : $i) . ':' . (($j < 10) ? ('0'.$j) : $j);
                self::$_time_arr[$time] = $time;
            }
        }

        return self::$_time_arr;
    }
}
