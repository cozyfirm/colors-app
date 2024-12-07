<?php

namespace App\Models\Social\Fans;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @method static create(array $array)
 * @method static where(string $string, string $string1, $id)
 */
class FanRequest extends Model{
    use HasFactory;

    protected $table = 'users__fans__requests';
    protected $guarded = [];

    /**
     * Get from who request is created
     * @return HasOne
     */
    public function fromRel(): HasOne{
        return $this->hasOne(User::class, 'id', 'from');
    }

    /**
     * Get to who request is sent
     * @return HasOne
     */
    public function toRel(): HasOne{
        return $this->hasOne(User::class, 'id', 'to');
    }
}
