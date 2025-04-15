<?php

namespace App\Models\SystemCore;

use App\Models\Chat\MCChat;
use App\Models\Core\Keyword;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;


class SeasonMatch extends Model{
    use HasFactory;

    protected $table = 'core__seasons__matches';
    protected $guarded = ['id'];

    public function date(): string{
        return Carbon::parse($this->date)->format('d.m.Y');
    }
    public function seasonRel(): HasOne{
        return $this->hasOne(Season::class, 'id', 'season_id');
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
    public function chatRel(): HasOne{
        return $this->hasOne(MCChat::class, 'id', 'chat_id');
    }
}
