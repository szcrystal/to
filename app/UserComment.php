<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserComment extends Model
{
    protected $fillable = [
        'img_id',
        'user_id',
        'main_user',
        'rep_user_id',
        'comment',
    ];
}
