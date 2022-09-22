<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NewsCategories extends Model
{

	protected $table = 'news_categories';
	
    protected $guard_name ='api';

    protected $fillable = [
        'id', 'name',
    ];
}
