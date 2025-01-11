<?php

namespace App\Models\Posts;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostFile extends Model{
    use HasFactory;

    protected $table = 'posts__files';
    protected $guarded = ['id'];
}
