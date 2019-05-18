<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TagRelation extends Model
{
    protected $fillable = [
        'img_id',
        'tag_id',
        'sort_num'
        //'view_count',
    ];
}
