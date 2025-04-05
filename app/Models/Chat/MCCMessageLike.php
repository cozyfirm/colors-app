<?php

namespace App\Models\Chat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, string $string1, mixed $message_id)
 * @method static create(array $array)
 */
class MCCMessageLike extends Model{
    use HasFactory;

    protected $table = 'chat__matchchat_messages_likes';
    protected $guarded = ['id'];
}
