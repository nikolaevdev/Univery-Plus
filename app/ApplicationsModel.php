<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
class ApplicationsModel extends Model
{
    protected $guard_name ='api';

    protected $table = 'applications';

    protected $casts = [
       'created_at' => 'datetime:d.m.Y H:i:s',
       'updated_at' => 'datetime:d.m.Y H:i:s',
    ];

    protected $fillable = [
        'id', 
        'status', 
        'indexer', 
        'user_id', 
        'created_at',
        'updated_at',
    ];
}
