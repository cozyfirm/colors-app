<?php

namespace App\Models\SystemCore;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static where(string $string, string $string1, $venue_id)
 * @method static create()
 */
class Venue extends Model{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'core__venues';
    protected $guarded = ['id'];

    public function clubRel(): HasOne{
        return $this->hasOne(Club::class, 'venue_id', 'id');
    }
}
