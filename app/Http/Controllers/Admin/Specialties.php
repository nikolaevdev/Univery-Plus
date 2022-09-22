<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Carbon;
use DB;
use Illuminate\Support\Collection;

class Specialties extends Controller
{
    /**
     *
     */
    
    function __construct()
    {
        $this->middleware(['role_or_permission:администратор|система']);
    }

    public function index()
    {
        $specialties = DB::table('specialties')
            ->leftjoin('specialties_info', 'specialties_info.specialty', '=', 'specialties.id')
            ->leftjoin('use', 'specialties_info.use', '=', 'use.id')
            ->leftjoin('stage_of_education', 'specialties.stage_of_education', '=', 'stage_of_education.id')
            ->select('specialties.*', 'use.id as use_id', 'use.name as use_name', 'stage_of_education.id as stage_of_education_id', 'stage_of_education.name as stage_of_education_name')

            ->get();

        $specialties_sort = $this->sort_array($specialties);

        return array('data' => $specialties_sort);
    }

    public function add(Request $request)
    {

        $this->validate($request, [
            'name' => ['required', 'string', 'max:200'],
            'description' => ['nullable', 'string'],
            'faculty' => ['nullable', 'integer'],
            'stage_of_education' => ['required', 'integer'],
            'direction_code' => ['nullable', 'string', 'max:20'],
            'duration_of_training' => ['nullable', 'string', 'max:20'],
            'full_time' => ['nullable', 'integer', 'max:1'],
            'full_time_correspondence_course' => ['nullable', 'integer', 'max:1'],
            'correspondence_course' => ['nullable', 'integer', 'max:1'],
            'correspondence_course_distance' => ['nullable', 'integer', 'max:1'],
            'checked_uses.*' => ['required', 'integer'],
        ]);

        $input = $request->all();

        $id_specialization = DB::table('specialties')->insertGetId(
            [
                'name' => $input['name'], 
                'description' => $request->filled('description') ? $input['description'] : '', 
                'faculty' => $request->filled('faculty') ? $input['faculty'] : '', 
                'stage_of_education' => $input['stage_of_education'], 
                'direction_code' => $input['direction_code'], 
                'duration_of_training' => $input['duration_of_training'], 
                'full_time' => $request->filled('full_time') ? $input['full_time'] : 0, 
                'full_time_correspondence_course' => $request->filled('full_time_correspondence_course') ? $input['full_time_correspondence_course'] : 0, 
                'correspondence_course' => $request->filled('correspondence_course') ? $input['correspondence_course'] : 0, 
                'correspondence_course_distance' => $request->filled('correspondence_course_distance') ? $input['correspondence_course_distance'] : 0, 
                'created_at' => Carbon::now(), 
                'updated_at' => Carbon::now()
           ]);
      

        $specialization_uses = [];
        for($i= 0; $i < count($input['checked_uses']); $i++){
            $specialization_uses[] = [
                'specialty' => $id_specialization,
                'use' => $input['checked_uses'][$i],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        DB::table('specialties_info')->where('specialty', $id_specialization)->delete();
        DB::table('specialties_info')->insert($specialization_uses);

        return response()->json(array(
            'ok' => 'ok', 
            'specialization' => array(
                'id' => $id_specialization, 
                'name' => $input['name'], 
                'description' => $request->filled('description') ? $input['description'] : '', 
                'stage_of_education' => $input['stage_of_education'], 
                 'faculty' => $request->filled('faculty') ? $input['faculty'] : '', 
                'direction_code' => $input['direction_code'], 
                'duration_of_training' => $input['duration_of_training'], 
                'full_time' => $request->filled('full_time') ? $input['full_time'] : 0, 
                'full_time_correspondence_course' => $request->filled('full_time_correspondence_course') ? $input['full_time_correspondence_course'] : 0, 
                'correspondence_course' => $request->filled('correspondence_course') ? $input['correspondence_course'] : 0, 
                'correspondence_course_distance' => $request->filled('correspondence_course_distance') ? $input['correspondence_course_distance'] : 0, 
                'checked_uses' => $input['checked_uses'] 
            )
        ));

    }

    public function update(Request $request)
    {

        $this->validate($request, [
            'specialization_id' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:35'],
            'description' => ['nullable', 'string'],
            'faculty' => ['nullable', 'integer'],
            'stage_of_education' => ['required', 'integer'],
            'direction_code' => ['nullable', 'string', 'max:20'],
            'duration_of_training' => ['nullable', 'string', 'max:20'],
            'full_time' => ['nullable', 'boolean'],
            'full_time_correspondence_course' => ['nullable', 'boolean'],
            'correspondence_course' => ['nullable', 'boolean'],
            'correspondence_course_distance' => ['nullable', 'boolean'],
            'checked_uses.*' => ['required', 'integer'],
        ]);

        $input = $request->all();

        DB::table('specialties')
           ->where('id', $input['specialization_id'])
           ->update(
            [
                'name' => $input['name'], 
                'description' => $input['description'], 
                'faculty' => $input['faculty'], 
                'stage_of_education' => $input['stage_of_education'], 
                'direction_code' => $input['direction_code'], 
                'duration_of_training' => $input['duration_of_training'], 
                'full_time' => $request->filled('full_time') ? $input['full_time'] : 0, 
                'full_time_correspondence_course' => $request->filled('full_time_correspondence_course') ? $input['full_time_correspondence_course'] : 0, 
                'correspondence_course' => $request->filled('correspondence_course') ? $input['correspondence_course'] : 0, 
                'correspondence_course_distance' => $request->filled('correspondence_course_distance') ? $input['correspondence_course_distance'] : 0, 
                'updated_at' => Carbon::now()
           ]);
      

        $specialization_uses = [];
        for($i= 0; $i < count($input['checked_uses']); $i++){
            $specialization_uses[] = [
                'specialty' => $input['specialization_id'],
                'use' => $input['checked_uses'][$i],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        DB::table('specialties_info')->where('specialty', $input['specialization_id'])->delete();
        DB::table('specialties_info')->insert($specialization_uses);

        return response()->json(array(
            'ok' => 'ok'
        ));

    }

    public function destroy(Request $request)
    {
        $validator = $this->validate($request, [
            'id' => ['required', 'integer'],
        ]);

        $input = $request->all();

        DB::table('specialties')
        ->where('id', $input['id'])
        ->delete();

        DB::table('specialties_info')
        ->where('id', $input['specialty'])
        ->delete();

        return response()->json(array('status' => 'ok'));
    }



    public function sort_array($array)
    {
        if(!$array)
            return false; 

        $return = array();
        $temp_val = '';
        $temp_specialization = -1;

        foreach($array as $key=>$val) {

            if($val->id == $temp_val){

                $return[$temp_specialization]['uses'][] = array('id' => $val->use_id, 'use_name' => $val->use_name);
               
            } else {
                $temp_val = $val->id;

                $temp_specialization++;
                
                $return[$temp_specialization] = array(
                    'id' => $val->id, 
                    'name' => $val->name,
                    'description' => $val->description, 
                    'faculty' => $val->faculty,
                    'stage_of_education' => $val->stage_of_education, 
                    'stage_of_education_name' => $val->stage_of_education_name, 
                    'direction_code' => $val->direction_code, 
                    'duration_of_training' => $val->duration_of_training, 
                    'full_time' => $val->full_time, 
                    'full_time_correspondence_course' => $val->full_time_correspondence_course, 
                    'correspondence_course' => $val->correspondence_course, 
                    'correspondence_course_distance' => $val->correspondence_course_distance, 
                    'uses' => array(
                        array(
                            'id1' => $val->use_id, 
                            'name' => $val->use_name
                        )
                    )
                );
            }

        }



        return $return;
    }
}