<?php

namespace App\Models\SystemCore\Users;

use App\Models\SystemCore\Club;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static create(array $array)
 */
class UserTeams extends Model{
    use HasFactory, SoftDeletes;

    protected $table = 'users__teams';
    protected $guarded = ['id'];

    public function teamRel(): HasOne{
        return $this->hasOne(Club::class, 'id', 'team');
    }
    public function nationalTeamRel(): HasOne{
        return $this->hasOne(Club::class, 'id', 'national_team');
    }
}
