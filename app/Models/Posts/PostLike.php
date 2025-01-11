<?php

namespace App\Models\Posts;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, string $string1, mixed $comment_id)
 */
class PostLike extends Model{
    use HasFactory;

    protected $table = 'posts__likes';
    protected $guarded = [];
}
