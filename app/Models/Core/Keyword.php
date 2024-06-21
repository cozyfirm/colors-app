<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static where(string $string, $key)
 */
class Keyword extends Model{
    use HasFactory;

    use SoftDeletes;

    protected static array $_keywords = [
        /* Questions keywords */
        'da_ne' => 'Da / Ne',
        'league_type' => 'League type',
        'league_option' => 'Kola lige'
    ];

    protected $table = '__keywords';
    protected $guarded = ['id'];

    /* Return all types of keywords */
    public static function getKeywords(): array{ return self::$_keywords; }
    public static function getKeyword($key) : string{ return self::$_keywords[$key]; }
    public static function getIt($key){ return Keyword::where('type', $key)->pluck('name', 'id'); }
    public static function getItByVal($key){ return Keyword::where('type', $key)->pluck('name', 'value'); }
}
