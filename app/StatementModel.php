<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
class StatementModel extends Model
{
    protected $guard_name ='api';

    protected $table = 'users_info';

    protected $casts = [
       'created_at' => 'datetime:d.m.Y H:i:s',
       'updated_at' => 'datetime:d.m.Y H:i:s',
    ];

    protected $fillable = [
        'id', 
        'user_id', 
        'name', 
        'surname', 
        'patronymic', 
        'full', 
        'sex', 
        'checkword', 
        'birthday', 
        'birthmonth', 
        'birthyear',
        'telephone', 
        'telephone2', 
        'identification_document_id', 
        'identification_document_series', 
        'identification_document_number', 
        'identification_division_code', 
        'identification_issued_by', 
        'identification_day', 
        'identification_month', 
        'identification_year', 
        'document_on_education_id',
        'identification_document_url', 
        'document_on_education_level_id', 
        
        'document_on_education_id', 
        'document_on_education_series', 
        'document_on_education_number', 
        'identification_division_code', 
        'document_on_education_day_of_issue', 
        'document_on_education_month', 
        'document_on_education_year', 

        'document_on_education_name_of_organization', 
        'document_on_education_organization_region', 
        'document_on_education_organization_city', 
        'document_on_education_year_of_receipt', 
        'document_on_education_year_of_completion', 
        'document_on_education_file_url', 
        'сitizenship', 
        'сitizenship_region',
        'сitizenship_city', 
        'сitizenship_street', 
        'сitizenship_house', 
        'сitizenship_building', 
        'сitizenship_flat', 
        'postalcode', 
        'сitizenship_region_actual', 
        'сitizenship_city_actual', 
        'сitizenship_street_actual', 
        'сitizenship_house_actual', 
        'сitizenship_building_actual', 
        'сitizenship_flat_actual', 
        'postalcode_actual', 
        'liable_for_military_service', 
        'comment', 
        'dormitories', 
        'benefits', 
        'individual_achievements', 
        'status',
        'created_at',
        'updated_at',
    ];
}
