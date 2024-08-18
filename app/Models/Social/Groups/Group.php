<?php

namespace App\Models\Social\Groups;

use App\Models\Core\MyFile;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static create(array $array)
 */
class Group extends Model{
    use HasFactory, SoftDeletes;

    protected $table = 'groups';
    protected $guarded = ['id'];

    public function fileRel(): HasOne{
        return $this->hasOne(MyFile::class,'id', 'file_id');
    }
    public function adminsRel(): HasMany{
        return $this->hasMany(GroupMember::class, 'group_id', 'id')->where('role', 'admin');
    }

    /** Helper methods */

    /**
     * @param $user_id
     * @return bool
     *
     * Check if particular user is admin of the group
     */
    public function isAdmin($user_id): bool {
        foreach ($this->adminsRel as $admin){
            if($admin->user_id == $user_id) return true;
        }

        return false;
    }
}
