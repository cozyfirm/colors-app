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
 * @method static where(string $string, mixed $id)
 */
class League extends Model{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'core__leagues';
    protected $guarded = ['id'];

    public function typeRel(): HasOne{
        return $this->hasOne(Keyword::class, 'value', 'type')->where('type', 'league_type');
    }
    public function countryRel(): HasOne{ return $this->hasOne(Countries::class, 'id', 'country_id'); }
    public function seasonRel(): HasOne{
        return $this->hasOne(Season::class, 'league_id', 'id')->orderBy('id', 'DESC');
    }
    public function seasonsRel(): HasMany{
        return $this->hasMany(Season::class, 'league_id', 'id')->orderBy('id', 'DESC');
    }
}
