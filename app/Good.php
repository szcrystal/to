<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Good extends Model
{
    protected $fillable = [
        'user_id',
        'img_id',
    ];

}
