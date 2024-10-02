<?php

namespace App\Models\SystemCore;

use App\Models\Core\Countries;
use App\Models\Core\Keyword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static where(string $string, string $string1, int $int)
 */
class Club extends Model{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'core__clubs';
    protected $guarded = ['id'];

    public function countryRel(): HasOne{ return $this->hasOne(Countries::class, 'id', 'country_id'); }
    public function nationalRel(): HasOne{ return $this->hasOne(Keyword::class, 'value', 'national')->where('type', 'da_ne'); }
    public function venueRel(): HasOne{ return $this->hasOne(Venue::class, 'id', 'venue_id'); }
    public function genderRel(): HasOne{ return $this->hasOne(Keyword::class, 'value', 'gender')->where('type', 'gender'); }

    /* Last season */
    public function lastSeasonRel(): HasOne{
        return $this->hasOne(SeasonTeam::class, 'team_id', 'id')->orderBy('season_id', 'DESC');
    }
    public function allSeasons(): HasMany{
        return $this->hasMany(SeasonTeam::class, 'team_id', 'id')->orderBy('season_id', 'DESC');
    }
}
