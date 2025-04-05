<?php

namespace App\Models\Chat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, string $string1, mixed $chat_id)
 */
class MCChat extends Model{
    use HasFactory;

    protected $table = 'chat__matchchat';
    protected $guarded = ['id'];
}
