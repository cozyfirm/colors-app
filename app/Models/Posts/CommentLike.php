<?php

namespace App\Models\Posts;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $array)
 * @method static where(string $string, string $string1, mixed $comment_id)
 */
class CommentLike extends Model{
    use HasFactory;

    protected $table = 'posts__comments_likes';
    protected $guarded = [];
}
