<?php

namespace App\Traits\Common;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

trait CommonTrait{
    protected static array $_time_arr = [];

    /**
     * @param int $from
     * @param int $to
     * @return array
     *
     * Return array of times
     */
    public static function formTimeArr(int $from = 0, int $to = 23): array{
        for($i=$from; $i<= $to; $i++){
            for($j=0; $j<60; $j+=15){
                $time = (($i < 10) ? ('0'.$i) : $i) . ':' . (($j < 10) ? ('0'.$j) : $j);
                self::$_time_arr[$time] = $time;
            }
        }

        return self::$_time_arr;
    }

    /**
     * @param $key
     * @return array|string
     *
     * Generate random hash without slash and ampersand
     */
    public function randomHash($key): array|string {
        $hash = Hash::make(md5(time() . $key ));
        $hash = str_replace('/', '-', $hash);
        return str_replace('&', '-', $hash);
    }
}
