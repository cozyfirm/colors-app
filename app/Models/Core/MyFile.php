<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, string $string1, $id)
 * @method static create(array $array)
 */
class MyFile extends Model{
    use HasFactory;

    protected $table = '__files';
    protected $guarded = ['id'];
}