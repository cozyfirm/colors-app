<?php

namespace App\Models\SystemCore\Users;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static create(array $array)
 */
class UserTeams extends Model{
    use HasFactory, SoftDeletes;

    protected $table = 'users__teams';
    protected $guarded = ['id'];
}
