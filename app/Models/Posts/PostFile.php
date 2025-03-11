<?php

namespace App\Models\Posts;

use App\Models\Core\MyFile;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @method static where(string $string, string $string1, $id)
 */
class PostFile extends Model{
    use HasFactory;

    protected $table = 'posts__files';
    protected $guarded = ['id'];

    public function fileRel(): HasOne{
        return $this->hasOne(MyFile::class, 'id', 'file_id');
    }
}
