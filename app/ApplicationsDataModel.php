<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
class ApplicationsDataModel extends Model
{
    protected $guard_name ='api';

    protected $table = 'applications_data';

    protected $casts = [
       'created_at' => 'datetime:d.m.Y H:i:s',
       'updated_at' => 'datetime:d.m.Y H:i:s',
    ];

    protected $fillable = [
        'id', 
        'application_id', 
        'specialty_id', 
        'statement_file_url', 
        'created_at',
        'updated_at',
    ];
}
