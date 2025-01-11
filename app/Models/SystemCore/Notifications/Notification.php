<?php

namespace App\Models\SystemCore\Notifications;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @method static create(array $array)
 * @method static where(string $string, string $string1, $id)
 */
class Notification extends Model{
    use HasFactory;

    protected $table = 'notifications';
    protected $guarded = ['id'];

    public function fanRequestRel(): HasOne{
        return $this->hasOne(NotificationFanRequest::class, 'notification_id', 'id');
    }
}
