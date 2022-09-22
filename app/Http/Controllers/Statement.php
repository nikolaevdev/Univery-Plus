<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\User;
use App\StatementModel;
use App\ApplicationsModel;
use App\ApplicationsDataModel;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use DB;
use Illuminate\Support\Collection;
use Auth;
use Illuminate\Support\Facades\Storage;

class Statement extends Controller
{

    private $image_ext = ['jpg', 'jpeg', 'png'];
    private $document_ext = ['pdf'];
    /**
     *
     */
    
    public function index()
    {
        $statement_my = DB::table('users_info')->select('full', 'status')->where('user_id', Auth::id())->first();

        $applications_list = DB::table('applications')
            ->where('user_id', Auth::id())
            ->select('applications.*')
            ->get();

        $status = StatementModel::where('user_id', Auth::id())
        ->select('status')
        ->first();

        return response()->json(array('statement_my' =>  $statement_my, 'applications_list' =>  $applications_list, 'status' => $status));
    }

    private function quickRandom($length = 16)
    {
        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        return substr(str_shuffle(str_repeat($pool, 5)), 0, $length);
    }

    public function infodata_form(Request $request)
    {
        $identity_documents = DB::table('identification_documents_name')->select('id', 'name')->get();
        $documents_on_education = DB::table('document_on_education_name')->select('id', 'name')->get();

        return response()->json(array('identity_documents' => $identity_documents, 'documents_on_education' => $documents_on_education,));
    }

    protected function step2(Request $request)
    {
        
        $validator = $this->validate($request, [
            'name' => ['required', 'string', 'max:31'],
            'surname' => ['required', 'string', 'max:12'],
            'patronymic'  => ['required', 'string', 'max:2077'],
            'checkword'  => ['nullable', 'string', 'max:2077'],
            'birthday' => ['required', 'integer', 'max:31'],
            'birthmonth' => ['required', 'integer', 'max:12'],
            'birthyear'  => ['required', 'integer', 'max:2077'],
            'telephone' => ['required', 'string', 'max:30'],
            'telephone2' => ['nullable', 'integer', 'max:30'],
            'identification_document_id' => ['required', 'integer'],
            'identification_document_series' => ['required', 'string', 'max:12'],
            'identification_document_number' => ['required', 'string', 'max:12'], 
            'identification_division_code' => ['required', 'string'], 
            'identification_issued_by' =>  ['required', 'string'], 
            'identification_day' => ['required', 'integer', 'max:31'],
            'identification_month' => ['required', 'integer', 'max:12'],
            'identification_year'  => ['required', 'integer', 'max:2077'],
            'document_on_education_id' => ['required', 'integer'],
            'document_on_education_series' => ['required', 'string', 'max:12'],
            'document_on_education_number' => ['required', 'string', 'max:12'], 
            'document_on_education_day_of_issue' => ['required', 'integer', 'max:31'],
            'document_on_education_month' => ['required', 'integer', 'max:12'],
            'document_on_education_year' => ['required', 'integer', 'max:2077'],
            'document_on_education_name_of_organization' => ['required', 'string'],
            'document_on_education_organization_region' => ['required', 'string'], 
            'document_on_education_organization_city' => ['required', 'string'],
            'document_on_education_year_of_receipt' => ['required', 'integer', 'min:1900', 'max:2077'],
            'document_on_education_year_of_completion' => ['required', 'integer', 'min:1900', 'max:2077'],
            'сitizenship' => ['required', 'string', 'max:25'],
            'сitizenship_region' => ['required', 'string', 'max:25'],
            'сitizenship_city' => ['required', 'string', 'max:100'], 
            'сitizenship_street' => ['required', 'string', 'max:255'], 
            'сitizenship_house' => ['required', 'integer'], 
            'сitizenship_flat' => ['required', 'integer'], 
            'building' => ['sometimes', 'nullable', 'integer', 'max:250'], 
            'postalcode' => ['required', 'string', 'max:6'], 
            'сitizenship_region_actual' => ['nullable', 'string', 'max:25'],
            'сitizenship_city_actual' => ['nullable', 'string', 'max:100'], 
            'сitizenship_street_actual' => ['nullable', 'string', 'max:255'], 
            'сitizenship_house_actual' => ['nullable', 'integer', 'max:250'],  
            'сitizenship_flat_actual' => ['nullable', 'integer', 'max:90'], 
            'postalcode_actual' => ['nullable', 'string', 'max:6'], 
        ]);

        $input = $request->all();

        $user = $request->user();

        $input['full'] = $input['name'].' '.$input['surname'].' '.$input['patronymic'];
        $input['user_id'] = $user->id;
        $input['status'] = 1;

        $statement = StatementModel::create($input);

        return $statement;
    }

    public function my_form(Request $request)
    {

        $user = $request->user();

        $form = StatementModel::where('user_id', $user->id)
        ->select('*')
        ->first();

        return response()->json(array('ok' => 'ok', 'form'=> $form));
    }

    public function my_form_status(Request $request)
    {

        $user = $request->user();

        $status = StatementModel::where('user_id', $user->id)
        ->select('status')
        ->first();

        return $status;
    }

   public function add_files(Request $request)
    {

        $this->validate($request, [
            'file_identification_document' => ['required', 'mimes:jpeg,jpg,pdf', 'max:10000'],
            'file_document_on_education' => ['required', 'mimes:jpeg,jpg,pdf', 'max:10000'],
        ]);

        if ($request->hasFile('file_identification_document'))
        {
            if ($request->file('file_identification_document')->isValid()) {

                $fileName = $this->quickRandom(8).'.statement.'.$request->file_identification_document->extension();
                $file_identification_document = $request->file('file_identification_document')->storeAs('uploads', $fileName, 'public');
        
            } 
        }  

        if ($request->hasFile('file_document_on_education'))
        {
            if ($request->file('file_document_on_education')->isValid()) {

                $fileName = $this->quickRandom(8).'.statement.'.$request->file_document_on_education->extension();
                $file_document_on_education = $request->file('file_document_on_education')->storeAs('uploads', $fileName, 'public');
        
            } 
        }

        $input = $request->all();

        if ($request->file('file_identification_document')->isValid() && $request->file('file_document_on_education')->isValid()) {

            $statement_info = DB::table('users_info')
           ->where('user_id', Auth::id())
           ->select('identification_document_url', 'document_on_education_file_url')
           ->first();

            if($statement_info) {
                if($statement_info->identification_document_url)
                    Storage::disk('public')->delete($statement_info->identification_document_url);

                if($statement_info->document_on_education_file_url)
                    Storage::disk('public')->delete($statement_info->document_on_education_file_url);
             
            }

            StatementModel::where('user_id', Auth::id())
              ->update(['status' => 2, 'identification_document_url' => $file_identification_document, 'document_on_education_file_url' => $file_document_on_education, 
                'updated_at' => Carbon::now()]);
        }

        return response()->json(array('ok' => 'ok'));

    }

    public function get_file_identification_document(Request $request)
    {

        $identification_document = StatementModel::where('user_id', Auth::id())
        ->select('identification_document_url', 'full')
        ->first();

        if($identification_document['identification_document_url']){
            $ext = pathinfo(storage_path().'/uploads/'.$identification_document['identification_document_url'], PATHINFO_EXTENSION);

            return Storage::disk('public')->download($identification_document['identification_document_url'], $identification_document['full'].'-Identification Document.'.$ext);
        } else abort('404');

    }

    public function get_file_document_on_education(Request $request)
    {

        $document_on_education = StatementModel::where('user_id', Auth::id())
        ->select('document_on_education_file_url', 'full')
        ->first();

        if($document_on_education['document_on_education_file_url']){
            $ext = pathinfo(storage_path().'/uploads/'.$document_on_education['document_on_education_file_url'], PATHINFO_EXTENSION);

            return Storage::disk('public')->download($document_on_education['document_on_education_file_url'], $document_on_education['full'].'-Document On Education.'.$ext);

        } else abort('404');

    }

    protected function step1_edit_save(Request $request)
    {
        
        $validator = $this->validate($request, [
            'name' => ['required', 'string', 'max:31'],
            'surname' => ['required', 'string', 'max:12'],
            'patronymic'  => ['required', 'string', 'max:2077'],
            'checkword'  => ['nullable', 'string', 'max:2077'],
            'birthday' => ['required', 'integer', 'max:31'],
            'birthmonth' => ['required', 'integer', 'max:12'],
            'birthyear'  => ['required', 'integer', 'max:2077'],
            'telephone' => ['required', 'string', 'max:30'],
            'telephone2' => ['nullable', 'integer', 'max:30'],
            'identification_document_id' => ['required', 'integer'],
            'identification_document_series' => ['required', 'integer', 'max:9999'],
            'identification_document_number' => ['required', 'integer', 'max:999999'], 
            'identification_division_code' => ['required', 'string'], 
            'identification_issued_by' =>  ['required', 'string'], 
            'identification_day' => ['required', 'integer', 'max:31'],
            'identification_month' => ['required', 'integer', 'max:12'],
            'identification_year'  => ['required', 'integer', 'max:2077'],
            'document_on_education_id' => ['required', 'integer'],
            'document_on_education_series' => ['required', 'integer', 'max:9999'],
            'document_on_education_number' => ['required', 'integer', 'max:999999'], 
            'document_on_education_day_of_issue' => ['required', 'integer', 'max:31'],
            'document_on_education_month' => ['required', 'integer', 'max:12'],
            'document_on_education_year' => ['required', 'integer', 'max:2077'],
            'document_on_education_name_of_organization' => ['required', 'string'],
            'document_on_education_organization_region' => ['required', 'string'], 
            'document_on_education_organization_city' => ['required', 'string'],
            'document_on_education_year_of_receipt' => ['required', 'integer', 'min:1900', 'max:2077'],
            'document_on_education_year_of_completion' => ['required', 'integer', 'min:1900', 'max:2077'],
            'сitizenship' => ['required', 'string', 'max:25'],
            'сitizenship_region' => ['required', 'string', 'max:25'],
            'сitizenship_city' => ['required', 'string', 'max:100'], 
            'сitizenship_street' => ['required', 'string', 'max:255'], 
            'сitizenship_house' => ['required', 'integer'], 
            'сitizenship_flat' => ['required', 'integer'], 
            'building' => ['sometimes', 'nullable', 'integer', 'max:250'], 
            'postalcode' => ['required', 'integer', 'max:999999'], 
            'сitizenship_region_actual' => ['nullable', 'string', 'max:25'],
            'сitizenship_city_actual' => ['nullable', 'string', 'max:100'], 
            'сitizenship_street_actual' => ['nullable', 'string', 'max:255'], 
            'сitizenship_house_actual' => ['nullable', 'integer', 'max:250'],  
            'сitizenship_flat_actual' => ['nullable', 'integer', 'max:90'], 
            'postalcode_actual' => ['nullable', 'string', 'max:6'], 
            'liable_for_military_service'  => ['nullable', 'boolean'],
            'comment' => ['nullable', 'string'], 
            'dormitories'  => ['nullable', 'boolean'],
            'benefits'  => ['nullable', 'boolean'],
            'individual'  => ['nullable', 'boolean'],
            'updated_at' => Carbon::now()
        ]);

        $input = $request->all();

        $user = $request->user();

        $input['full'] = $input['name'].' '.$input['surname'].' '.$input['patronymic'];
        $input['user_id'] = $user->id;

        $statement = StatementModel::where('user_id', Auth::id())
              ->update($input);

        return response()->json(array('ok' => 'ok'));
    }

    public function get_specializations_info(Request $request){

        $specialties = DB::table('specialties')
            ->leftjoin('specialties_info', 'specialties_info.specialty', '=', 'specialties.id')
            ->leftjoin('stage_of_education', 'specialties.stage_of_education', '=', 'stage_of_education.id')
            ->leftjoin('faculties', 'specialties.faculty', '=', 'faculties.id')
            ->select('specialties.*', 'stage_of_education.id as stage_of_education_id', 'stage_of_education.name as stage_of_education_name', 'faculties.name as faculties_name')
            ->get();

        $specialties_sort = $this->sort_specialties_array($specialties);
        
        return $specialties_sort;

    }

    public function get_specialization_info(Request $request){

        $validator = $this->validate($request, [
            'specialization' => ['required', 'integer'],
        ]);

        $input = $request->all();

        $specialization = DB::table('specialties')
            ->leftjoin('specialties_info', 'specialties_info.specialty', '=', 'specialties.id')
            ->leftjoin('use', 'specialties_info.use', '=', 'use.id')
            ->leftjoin('stage_of_education', 'specialties.stage_of_education', '=', 'stage_of_education.id')
            ->leftjoin('faculties', 'specialties.faculty', '=', 'faculties.id')
            ->where('specialties.id', $input['specialization'])
            ->select('specialties.*', 'use.id as use_id', 'use.name as use_name', 'stage_of_education.id as stage_of_education_id', 'stage_of_education.name as stage_of_education_name', 'faculties.id as faculties_id', 'faculties.name as faculties_name')

            ->get();

        $specialization_sort = $this->sort_specialization_array($specialization);

        return $specialization_sort;

    }

    public function sort_specialties_array($array)
    {
        if(!$array)
            return false; 


        $return = array();

        $stage_of_education_val = '';
        $stage_of_education = -1;

        $specialization_keys = array();

        foreach($array as $key=>$val) {
            if($val->stage_of_education_id == $stage_of_education_val){

                $temp_specialization_val  = '';
                $temp_specialization = -1;

                foreach($array as $key1=>$val1) {
                   if($val->stage_of_education_id == $stage_of_education_val && $val1->id == $temp_specialization_val){

                       if (!in_array($val1->id, $specialization_keys)) {

                            $return[$stage_of_education]['specialties'][$temp_specialization] = array(
                                'id' => $val1->id, 
                                'name' => $val1->name,
                                'description' => $val1->description, 
                                'faculties_name' => $val1->faculties_name,
                                'direction_code' => $val1->direction_code, 
                                'duration_of_training' => $val1->duration_of_training,
                                'full_time' => $val1->full_time, 
                                'full_time_correspondence_course' => $val1->full_time_correspondence_course,
                                'correspondence_course' => $val1->correspondence_course, 
                                'correspondence_course_distance' => $val1->correspondence_course_distance
                            );

                        }
                       
                    } else {

                        $temp_specialization_val = $val->id;
                        $temp_specialization++;

                    }
                }

            } else {

                $stage_of_education_val = $val->stage_of_education_id;
                $stage_of_education++;
                
                $return[$stage_of_education] = array(
                    'stage_of_education_id' => $val->stage_of_education_id, 
                    'stage_of_education_name' => $val->stage_of_education_name,
                    'specialties' => array(

                        array(
                            'id' => $val->id, 
                            'name' => $val->name,
                            'description' => $val->description, 
                            'faculties_name' => $val->faculties_name,
                            'direction_code' => $val->direction_code, 
                            'duration_of_training' => $val->duration_of_training,
                            'full_time' => $val->full_time, 
                            'full_time_correspondence_course' => $val->full_time_correspondence_course,
                            'correspondence_course' => $val->correspondence_course, 
                            'correspondence_course_distance' => $val->correspondence_course_distance
                        )

                    )
                );

                array_push ($specialization_keys, $val->id);
            }
        }

        return $return;

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

    public function application_step2(Request $request)
    {

        $this->validate($request, [
            'specialization_id' => ['required', 'integer'],
            'file_statement' => ['required', 'mimes:jpeg,jpg,pdf', 'max:10000'],
        ]);

        if ($request->hasFile('file_statement'))
        {
            if ($request->file('file_statement')->isValid()) {

                $fileName = $this->quickRandom(8).'.application.'.$request->file_statement->extension();
                $file_statement = $request->file('file_statement')->storeAs('uploads', $fileName, 'public');
        
            } 
        }  

        $input = $request->all();

        if ($request->file('file_statement')->isValid()) {

            $date_info = Carbon::now();

            $statement = ApplicationsModel::create([
                'status' => '1',
                'indexer' => $date_info->year.'-'.$date_info->month.'-'.$this->quickRandom(8),
                'user_id' => Auth::id()
            ]);

            ApplicationsDataModel::create([
                'application_id' => $statement->id,
                'specialty_id' => $input['specialization_id'],
                'statement_file_url' => $file_statement
            ]);
        }

        return response()->json(array('ok' => 'ok', 'id'=> $statement->id));

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
        ->where('applications.user_id', Auth::id())
        ->select('applications_data.statement_file_url', 'applications.indexer')
        ->first();

        if($statement->statement_file_url){
            $ext = pathinfo(storage_path().'/uploads/'.$statement->statement_file_url, PATHINFO_EXTENSION);

            return Storage::disk('public')->download($statement->statement_file_url, $statement->indexer.'.'.$ext);

        } else abort('404');

    }

    public function get_application_my_info(Request $request){

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
            ->where('applications.user_id', Auth::id())
            ->select('specialties.*', 'use.id as use_id', 'use.name as use_name', 'stage_of_education.id as stage_of_education_id', 'stage_of_education.name as stage_of_education_name', 'faculties.id as faculties_id', 'faculties.name as faculties_name')

            ->get();

        $application = DB::table('applications')
        ->leftjoin('applications_data', 'applications_data.application_id', '=', 'applications.id')
        ->where('applications.id', $input['id'])
        ->where('applications.user_id', Auth::id())
        ->select('applications_data.statement_file_url', 'applications.*')
        ->first();

        $specialization_sort = $this->sort_specialization_array($specialization);

        return response()->json(array('status' => 'ok', 'specialization' => $specialization_sort, 'application' => $application));

    }

    public function application_step2_edit(Request $request)
    {

        $this->validate($request, [
            'id' => ['required', 'integer'],
            'file_statement' => ['required', 'mimes:jpeg,jpg,pdf', 'max:10000'],
        ]);

        if ($request->hasFile('file_statement'))
        {
            if ($request->file('file_statement')->isValid()) {

                $fileName = $this->quickRandom(8).'.application.'.$request->file_statement->extension();
                $file_statement = $request->file('file_statement')->storeAs('uploads', $fileName, 'public');
        
            } 
        }  

        $input = $request->all();

        if ($request->file('file_statement')->isValid()) {

            $application_info = DB::table('applications')
            ->leftjoin('applications_data', 'applications_data.application_id', '=', 'applications.id')
            ->where('applications.id', Auth::id())
            ->where('applications.user_id', $input['id'])
            ->select('applications_data.statement_file_url', 'applications.id', 'applications.user_id')
            ->first();

            if($application_info) {
                if($application_info->statement_file_url)
                    Storage::disk('public')->delete($application_info->statement_file_url);
             
            }

            if($application_info) {
                if($application_info->id == $input['id'] && $application_info->user_id == Auth::id()){

                    DB::table('applications')
                        ->where('id', $input['id'])
                        ->where('user_id', Auth::id())
                        ->update(['status' => 3, 'updated_at' => Carbon::now()]);

                    DB::table('applications_data')
                        ->where('application_id', $input['id'])
                        ->update(['applications_data.statement_file_url' => $file_statement, 'updated_at' => Carbon::now()]);

                }


            }


            return response()->json(array('ok' => 'ok'));
        }


       return response()->json(array('status' => 'error'));
    }

    public function application_destroy(Request $request)
    {
        $validator = $this->validate($request, [
            'id' => ['required', 'integer'],
        ]);

        $input = $request->all();

        $application = DB::table('applications')
           ->leftjoin('applications_data', 'applications_data.application_id', '=', 'applications.id')
           ->where('applications.id', $input['id'])
           ->where('applications.user_id', Auth::id())
           ->select('applications.id', 'applications_data.statement_file_url')
           ->first();

        if($application) {

            if($application->statement_file_url){
                Storage::disk('public')->delete($application->statement_file_url);
            }

            DB::table('applications')
           ->where('id', $input['id'])
           ->delete();

           DB::table('applications_data')
           ->where('application_id', $input['id'])
           ->delete();
        }

       return response()->json(array('status' => 'ok'));
    }

}