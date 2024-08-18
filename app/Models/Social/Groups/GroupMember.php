<?php

namespace App\Models\Social\Groups;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static create(array $array)
 */
class GroupMember extends Model{
    use HasFactory, SoftDeletes;

    protected $table = 'groups__members';
    protected $guarded = ['id'];
}
