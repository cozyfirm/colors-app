<?php

namespace App\Models\Social\Fans;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fan extends Model{
    use HasFactory, SoftDeletes;

    protected $table = 'users__fans';
    protected $guarded = [];


}
