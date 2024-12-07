<?php

namespace App\Models\Social\Fans;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static create(array $array)
 * @method static where(string $string, string $string1, $id)
 */
class Fan extends Model{
    use HasFactory, SoftDeletes;

    protected $table = 'users__fans';
    protected $guarded = [];

    public function userRel(): HasOne{
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    public function fanRel(): HasOne{
        return $this->hasOne(User::class, 'id', 'fan_id');
    }
}
