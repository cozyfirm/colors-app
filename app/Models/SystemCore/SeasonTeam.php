<?php

namespace App\Models\SystemCore;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static where(string $string, int $seasonID)
 * @method static create(array $array)
 */
class SeasonTeam extends Model{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'core__seasons__teams';
    protected $guarded = [];

    public function teamRel(): hasOne{
        return $this->hasOne(Club::class, 'id', 'team_id');
    }
    public function seasonRel(): HasOne{
        return $this->hasOne(Season::class, 'id', 'season_id');
    }
}
