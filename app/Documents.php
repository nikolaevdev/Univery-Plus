<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Documents extends Model
{
    protected $guard_name ='api';

     protected $casts = [
       'created_at' => 'datetime:d.m.Y H:i:s',
       'updated_at' => 'datetime:d.m.Y H:i:s',
    ];

    protected $fillable = [
        'id', 'name', 'category', 'description', 'credentials', 'created_at', 'updated_at'
    ];

    protected $hidden = [
        'user_id', 'type', 'file_url',
    ];

}
