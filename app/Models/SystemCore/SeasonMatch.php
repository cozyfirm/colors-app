<?php

namespace App\Models\SystemCore;

use App\Models\Core\Keyword;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @method static where(string $string, mixed $id)
 */
class SeasonMatch extends Model{
    use HasFactory;

    protected $table = 'core__seasons__matches';
    protected $guarded = ['id'];

    public function date(){
        return Carbon::parse($this->date)->format('d.m.Y');
    }
    public function homeRel(): HasOne{
        return $this->hasOne(Club::class, 'id', 'home_team');
    }
    public function visitorRel(): HasOne{
        return $this->hasOne(Club::class, 'id', 'visiting_team');
    }
    public function optionsRel(): HasOne{
        return $this->hasOne(Keyword::class, 'value', 'options')->where('type', 'league_option');
    }
}
