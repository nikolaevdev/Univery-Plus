<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ArticlesCategories extends Model
{

	protected $table = 'articles_categories';
	
    protected $guard_name ='api';

    protected $fillable = [
        'id', 'name',
    ];
}
