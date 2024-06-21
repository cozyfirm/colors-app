<?php

namespace App\Models\Core;

use App\Models\Settings\Keyword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @method static pluck(string $string, string $string1)
 * @method static where(string $string, mixed $code)
 */
class Countries extends Model
{
    use HasFactory;

    protected $table = 'api__countries';
    protected $guarded = ['id'];

    public function isUsed(): HasOne{
        return $this->hasOne(Keyword::class, 'value', 'used')->where('type', 'da_ne');
    }
}
