<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use App\StatementModel;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Carbon;
use DB;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Validator;
use Illuminate\Support\Facades\Storage;

class Applications extends Controller
{
    /**
     *
     */
    
    function __construct()
    {
        $this->middleware(['role_or_permission:администратор|директор|приемнаякомиссия|управление пользователями']);
    }

    public function index(Request $request)
    {

        $data = json_decode($request->queryParams, true);
        $rules = [
            'global_search' => ['string'],
            'sort' => ['array'],
            'filters' => ['array'],
            'per_page' => ['integer', 'min:5', 'max:30'],
        ];

        $validator = Validator::make($data, $rules);
        if ($validator->passes()) {

            $applications =  DB::table('applications')
            ->leftjoin('applications_data', 'applications_data.application_id', '=', 'applications.id')
            ->leftjoin('users', 'users.id', '=', 'applications.user_id')
            ->leftjoin('users_info', 'users_info.user_id ', '=', 'users.id');

            if($data['global_search'] && !empty($data['global_search'])){

                $applications->where('applications.id', 'like', $data['global_search']);
                $applications->orWhere('users_info.full', 'like', $data['global_search']);
                $applications->orWhere('applications.indexer', 'like', $data['global_search']);

            }

            if($data['filters'] && !empty($data['filters'])){

                foreach($data['filters'] as $filter){

                    if($filter['name'] == 'status'){

                        $applications->whereIn('status', $filter['selected_options']);

                    } else if($filter['name'] == 'id'){

                        $applications->where('applications.id', 'like', '%'.$filter['text'].'%');

                    } else if($filter['name'] == 'user_full'){

                        $applications->where('users_info.full', 'like', '%'.$filter['text'].'%');

                    } else if($filter['name'] == 'indexer'){

                        $applications->where('applications.indexer', 'like', '%'.$filter['text'].'%');
                    }


                }

            }

            if($data['sort'] && !empty($data['sort'])){

                foreach($data['sort'] as $sort){

                    if($sort['name'] == 'status'){

                        if($sort['order'] == 'desc'){

                            $applications->orderBy('status', 'desc');

                        } else if($sort['order'] == 'asc'){

                            $applications->orderBy('status', 'asc');
                        }

                    } else if($sort['name'] == 'id'){

                        if($sort['order'] == 'desc'){

                            $applications->orderBy('applications.id', 'desc');

                        } else if($sort['order'] == 'asc'){
                            
                            $applications->orderBy('applications.id', 'asc');
                        }

                    } else if($sort['name'] == 'user_full'){

                        if($sort['order'] == 'desc'){

                            $applications->orderBy('users_info.full', 'desc');

                        } else if($sort['order'] == 'asc'){
                            
                            $applications->orderBy('users_info.full', 'asc');
                        }

                        
                    } else if($sort['name'] == 'indexer'){

                        if($sort['order'] == 'desc'){

                            $applications->orderBy('applications.indexer', 'desc');

                        } else if($sort['order'] == 'asc'){
                            
                            $applications->orderBy('applications.indexer', 'asc');
                        }
                        
                    }


                }

            }
            
            return $applications->select('applications.*', 'applications_data.statement_file_url as file_statement_file', 'users_info.full as user_full')
            ->paginate($data['per_page']);

        } else {
            $validator->errors()->all();
        }

    }

    public function status_edit(Request $request)
    {
        $validator = $this->validate($request, [
            'ids.*' => ['integer'],
            'status' => ['integer'],
        ]);

        $input = $request->all();

        DB::table('applications')
            ->whereIn('id', $input['ids'])
            ->update(['status' =>  $input['status'], 'updated_at' => Carbon::now()]);

        return response()->json(array('ok' => 'ok'));

    }

    public function get_file_statement_document(Request $request)
    {

        $validator = $this->validate($request, [
            'id' => ['required', 'integer'],
        ]);

        $input = $request->all();

        $statement = DB::table('applications')
        ->leftjoin('applications_data', 'applications_data.application_id', '=', 'applications.id')
        ->where('applications.id', $input['id'])
        ->select('applications_data.statement_file_url', 'applications.indexer')
        ->first();

        if($statement->statement_file_url){
            $ext = pathinfo(storage_path().'/uploads/'.$statement->statement_file_url, PATHINFO_EXTENSION);

            return Storage::disk('public')->download($statement->statement_file_url, $statement->indexer.'.'.$ext);

        } else abort('404');

    }

    public function get_file_identification_document(Request $request)
    {

        $validator = $this->validate($request, [
            'id' => ['required', 'integer'],
        ]);

        $input = $request->all();

        $identification_document = DB::table('applications')
        ->leftjoin('users_info', 'users_info.user_id', '=', 'applications.user_id')
        ->where('applications.id', $input['id'])
        ->select('users_info.identification_document_url', 'users_info.full')
        ->first();

        if($identification_document->identification_document_url){
            $ext = pathinfo(storage_path().'/uploads/'.$identification_documen->identification_document_url, PATHINFO_EXTENSION);

            return Storage::disk('public')->download($identification_document->identification_document_url, $identification_document->full.'-Identification Document.'.$ext);
        } else abort('404');

    }

    public function get_file_document_on_education(Request $request)
    {

        $validator = $this->validate($request, [
            'id' => ['required', 'integer'],
        ]);

        $input = $request->all();

        $document_on_education = DB::table('applications')
        ->leftjoin('users_info', 'users_info.user_id', '=', 'applications.user_id')
        ->where('applications.id', $input['id'])
        ->select('users_info.document_on_education_file_url', 'users_info.full')
        ->first();

        if($document_on_education->document_on_education_file_url){
            $ext = pathinfo(storage_path().'/uploads/'.$document_on_education->document_on_education_file_url, PATHINFO_EXTENSION);

            return Storage::disk('public')->download($document_on_education->document_on_education_file_url, $document_on_education->full.'-Document On Education.'.$ext);

        } else abort('404');

    }

    public function getinfo(Request $request){

        $validator = $this->validate($request, [
            'id' => ['required', 'integer'],
        ]);

        $input = $request->all();

        $specialization = DB::table('applications')
            ->leftjoin('applications_data', 'applications_data.application_id', '=', 'applications.id')
            ->leftjoin('specialties', 'specialties.id', '=', 'applications_data.specialty_id')
            ->leftjoin('specialties_info', 'specialties_info.specialty', '=', 'specialties.id')
            ->leftjoin('use', 'specialties_info.use', '=', 'use.id')
            ->leftjoin('stage_of_education', 'specialties.stage_of_education', '=', 'stage_of_education.id')
            ->leftjoin('faculties', 'specialties.faculty', '=', 'faculties.id')
            ->where('applications.id', $input['id'])
            ->select('specialties.*', 'use.id as use_id', 'use.name as use_name', 'stage_of_education.id as stage_of_education_id', 'stage_of_education.name as stage_of_education_name', 'faculties.id as faculties_id', 'faculties.name as faculties_name')

            ->get();

        $application = DB::table('applications')
        ->leftjoin('applications_data', 'applications_data.application_id', '=', 'applications.id')
        ->where('applications.id', $input['id'])
        ->select('applications_data.statement_file_url', 'applications.*')
        ->first();

        $form = StatementModel::where('user_id', $application->user_id)
        ->select('*')
        ->first();

        $specialization_sort = $this->sort_specialization_array($specialization);

        return response()->json(array('status' => 'ok', 'specialization' => $specialization_sort, 'application' => $application, 'form'=> $form));

    }

    public function sort_specialization_array($array)
    {
        if(!$array)
            return false; 

        $return = array();
        $temp_val = '';

        foreach($array as $key=>$val) {

            if($val->id == $temp_val){

                $return['uses'][] = array('id' => $val->use_id, 'name' => $val->use_name);
               
            } else {
                $temp_val = $val->id;

                $return = array(
                    'id' => $val->id, 
                    'name' => $val->name,
                    'description' => $val->description, 
                    'faculties_name' => $val->faculties_name,
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
                            'id' => $val->use_id, 
                            'name' => $val->use_name
                        )
                    )
                );
            }

        }

        return $return;
    }

    public function destroy(Request $request)
    {
        $validator = $this->validate($request, [
            'ids.*' => ['integer'],
        ]);

        $input = $request->all();

        DB::table('applications')
            ->whereIn('id', $input['ids'])
            ->delete();

        $application_data = DB::table('applications_data')
           ->whereIn('application_id', $input['ids'])
           ->select('statement_file_url')
           ->get();

        if($application_data) {

            foreach($application_data as $data){

                if($data->statement_file_url){
                    Storage::disk('public')->delete($data->statement_file_url);
                }

            }

        }

        DB::table('applications_data')
        ->whereIn('application_id', $input['ids'])
        ->delete();

        return response()->json(array('status' => 'ok'));

    }

}