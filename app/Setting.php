<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'admin_name',
        'admin_email' ,
        'mail_footer',
        
        'is_product',
        
        'meta_title',
        'meta_description',
        'meta_keyword',
        
        'fix_need',
        'fix_other',
        
        'analytics_code',

    ];
    
}


