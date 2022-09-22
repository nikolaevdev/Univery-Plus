<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $guard_name ='api';

     protected $casts = [
       'created_at' => 'datetime:d.m.Y H:i:s',
       'updated_at' => 'datetime:d.m.Y H:i:s',
    ];

    protected $fillable = [
        'id', 'name', 'category', 'text', 'author', 'views', 'img_preview', 'status', 'created_at', 'updated_at'
    ];

}
