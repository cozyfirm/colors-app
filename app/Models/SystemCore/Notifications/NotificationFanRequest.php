<?php

namespace App\Models\SystemCore\Notifications;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @method static create(array $array)
 */
class NotificationFanRequest extends Model{
    use HasFactory;

    protected $table = 'notifications__fan_requests';
    protected $guarded = ['id'];

    public function userRel(): HasOne{
        return $this->hasOne(User::class, 'id', 'from');
    }
}
