<?php

namespace App\Models\SystemCore;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @method static where(string $string, mixed $user_id)
 */
class LeagueModerator extends Model{
    use HasFactory;

    protected $table = 'core__leagues_moderators';
    protected $guarded = ['id'];

    public function userRel(): HasOne{
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
