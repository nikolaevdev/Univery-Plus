<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DocumentsCategories extends Model
{

	protected $table = 'documents_categories';
	
    protected $guard_name ='api';

    protected $fillable = [
        'id', 'name',
    ];
}
