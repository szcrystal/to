<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserImg extends Model
{
    protected $fillable = [
        'user_id',
        'img_path',
        'explain',
        'target_url',
        'sort_num',
        'open_status',
        'view_count',
    ];
}

