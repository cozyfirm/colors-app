<?php

namespace App\Models\SystemCore;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static where(string $string, mixed $id)
 */
class Season extends Model{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'core__seasons';
    protected $guarded = ['id'];

    /* Deprecated */
    public function leagueRel(): HasOne{ return $this->hasOne(League::class, 'id', 'league_id'); }
    public function clubRel(): HasOne{ return $this->hasOne(Club::class, 'id', 'club'); }

    public function teamRel(): HasMany{
        return $this->hasMany(SeasonTeam::class, 'season_id', 'id');
    }

    /* Clubs playing for that season */
    public function clubsRel(): HasMany{
        return $this->hasMany(SeasonTeam::class, 'season_id', 'id');
    }
    public function matchRel(): HasMany{
        return $this->hasMany(SeasonMatch::class, 'season_id', 'id')->orderBy('date', 'DESC');
    }
    public function futureMatchRel(): HasMany{
        return $this->hasMany(SeasonMatch::class, 'season_id', 'id')->whereDate('date', '>=', date('Y-m-d'))->orderBy('date', 'DESC');
    }

    public function previousMatchesCount(): string{
        return SeasonMatch::where('season_id', $this->id)->whereDate('date', '<', date('Y-m-d'))->count();
    }
    public function nextMatchesCount(): string{
        return SeasonMatch::where('season_id', $this->id)->whereDate('date', '>=', date('Y-m-d'))->count();
    }
    public function getFirstGame(){
        return SeasonMatch::where('season_id', $this->id)->orderBy('date')->first();
    }
}
