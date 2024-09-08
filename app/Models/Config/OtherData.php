<?php

namespace App\Models\Config;

use App\Models\Core\MyFile;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static create(array $array)
 * @method static where(string $string, $id)
 */
class OtherData extends Model{
    use HasFactory, SoftDeletes;

    protected $table = '__other_data';
    protected $guarded = ['id'];

    public function fileRel(): HasOne{
        return $this->hasOne(MyFile::class,'id', 'file_id');
    }
}
