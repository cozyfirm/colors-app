<?php

namespace App\Models\Posts;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @method static create(array $array)
 * @method static where(string $string, string $string1, mixed $post_id)
 */
class PostComment extends Model{
    use HasFactory;

    protected $table = 'posts__comments';
    protected $guarded = ['id'];

    public function userRel(): HasOne{
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function likesRel(): HasMany{
        return $this->hasMany(CommentLike::class, 'comment_id', 'id');
    }
}
