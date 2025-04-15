<?php

namespace App\Models\Chat;

use App\Models\Core\MyFile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class MCCMessage extends Model{
    use HasFactory;

    protected $table = 'chat__matchchat_messages';
    protected $guarded = ['id'];

    public function userRel(): HasOne{
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    public function photoRel(): HasOne{
        return $this->hasOne(MyFile::class, 'id', 'file_id');
    }
}
