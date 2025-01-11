<?php

namespace App\Traits\Common;
use App\Models\Core\DBLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

trait TimeTrait{
    protected $_short_months = ['Jan', 'Feb', 'Mar', 'Apr', 'Maj', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Dec'];
    /**
     *  Created at:
     *      1. Post
     *      2. Comment
     */
    public function sampleTime($dateTime): string{
        $dt = Carbon::parse($dateTime);

        return $dt->format('d') . ' ' . $this->_short_months[$dt->format('m') + 1] . " " . ($dt->format("H:i"));
    }
}
