<?php

namespace App\Models\Posts;

use App\Models\Core\MyFile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @method static where(string $string, string $string1, $id)
 * @method static create(array $array)
 */
class Post extends Model{
    use HasFactory;
    protected int $_number_of_comments = 10;
    protected int $_number_of_comments_in_preview = 2;

    protected $guarded = ['id'];

    public function filesRel(): HasMany{
        return $this->hasMany(PostFile::class,'post_id', 'id');
    }
    public function fileRel(): HasOne{
        return $this->hasOne(PostFile::class, 'post_id', 'id')->orderBy('id', 'DESC');
    }
    public function userRel(): HasOne{
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     *  Comments section
     */
    public function commentsRel(): HasMany{
        return $this->hasMany(PostComment::class, 'id', 'post_id')->whereNull('parent_id')->take($this->_number_of_comments);
    }

    /**
     * When fetching group posts, two main comments are fetched with post
     *
     * @return HasMany
     */
    public function popularCommentsRel(): HasMany{
        return $this->hasMany(PostComment::class, 'post_id', 'id')->whereNull('parent_id')->orderBy('likes')->take($this->_number_of_comments_in_preview);
    }
}
