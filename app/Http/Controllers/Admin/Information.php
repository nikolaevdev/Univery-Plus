<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Carbon;
use DB;
use Illuminate\Support\Collection;

class Information extends Controller
{
    /**
     *
     */
    
    function __construct()
    {
        $this->middleware(['role_or_permission:администратор|система']);
    }

    public function education_levels__get()
    {
        $list = DB::table('education_levels')->select('id', 'name')->orderBy('id','DESC')->get();

        return $list;
    }

    public function education_levels__delete(Request $request)
    {
        $validator = $this->validate($request, [
            'id' => ['required', 'integer'],
        ]);

        $input = $request->all();

        DB::table('education_levels')
        ->where('id', $input['id'])
        ->delete();

        return response()->json(array('status' => 'ok'));
    }

    public function education_levels__save(Request $request)
    {
        $validator = $this->validate($request, [
        	'id' => ['required', 'integer'],
            'name' => ['required', 'unique:education_levels,name,'.$request->id, 'max:255'],
        ]);

        $input = $request->all();

        $permission = DB::table('education_levels')
              ->where('id', $input['id'])
              ->update(['name' => $input['name']]);

        return response()->json(array('ok' => 'ok'));
    }

    public function education_levels__add(Request $request)
    {
        $validator = $this->validate($request, [
            'name' => ['required', 'unique:education_levels', 'max:255'],
        ]);

        $input = $request->all();

        $id = DB::table('education_levels')->insertGetId(
		    ['name' => $input['name'], 'created_at' => now(), 'updated_at' => now()],
		);

        return response()->json(array('ok' => 'ok', 'new_id' => $id));
    }

    ///
    ///
    ///
    
    public function faculties__get()
    {
        $list = DB::table('faculties')->select('id', 'name')->orderBy('id','DESC')->get();

        return $list;
    }

    public function faculties__delete(Request $request)
    {
        $validator = $this->validate($request, [
            'id' => ['required', 'integer'],
        ]);

        $input = $request->all();

        DB::table('faculties')
        ->where('id', $input['id'])
        ->delete();

        return response()->json(array('status' => 'ok'));
    }

    public function faculties__save(Request $request)
    {
        $validator = $this->validate($request, [
            'id' => ['required', 'integer'],
            'name' => ['required', 'unique:faculties,name,'.$request->id, 'max:255'],
        ]);

        $input = $request->all();

        $permission = DB::table('faculties')
              ->where('id', $input['id'])
              ->update(['name' => $input['name']]);

        return response()->json(array('ok' => 'ok'));
    }

    public function faculties__add(Request $request)
    {
        $validator = $this->validate($request, [
            'name' => ['required', 'unique:faculties', 'max:255'],
        ]);

        $input = $request->all();

        $id = DB::table('faculties')->insertGetId(
            ['name' => $input['name'], 'created_at' => now(), 'updated_at' => now()],
        );

        return response()->json(array('ok' => 'ok', 'new_id' => $id));
    }

    public function get_faculties_list(Request $request)
    {
        $faculties = DB::table('faculties')
            ->select('faculties.id as id', 'faculties.name as name')
            ->get();

        return response()->json($faculties);
    }

    ///
    ///
    ///
    
    public function use__get()
    {
        $list = DB::table('use')->select('id', 'name')->orderBy('id','DESC')->get();

        return $list;
    }

    public function use__delete(Request $request)
    {
        $validator = $this->validate($request, [
            'id' => ['required', 'integer'],
        ]);

        $input = $request->all();

        DB::table('use')
        ->where('id', $input['id'])
        ->delete();

        return response()->json(array('status' => 'ok'));
    }

    public function use__save(Request $request)
    {
        $validator = $this->validate($request, [
            'id' => ['required', 'integer'],
            'name' => ['required', 'unique:use,name,'.$request->id, 'max:255'],
        ]);

        $input = $request->all();

        $permission = DB::table('use')
              ->where('id', $input['id'])
              ->update(['name' => $input['name']]);

        return response()->json(array('ok' => 'ok'));
    }

    public function use__add(Request $request)
    {
        $validator = $this->validate($request, [
            'name' => ['required', 'unique:use', 'max:255'],
        ]);

        $input = $request->all();

        $id = DB::table('use')->insertGetId(
            ['name' => $input['name'], 'created_at' => now(), 'updated_at' => now()],
        );

        return response()->json(array('ok' => 'ok', 'new_id' => $id));
    }

    public function get_use_list(Request $request)
    {
        $use = DB::table('use')
            ->select('use.id as id', 'use.name as name')
            ->get();

        return response()->json($use);
    }

    ///
    ///
    ///
    
    public function identification_documents_name__get()
    {
        $list = DB::table('identification_documents_name')->select('id', 'name')->orderBy('id','DESC')->get();

        return $list;
    }

    public function identification_documents_name__delete(Request $request)
    {
        $validator = $this->validate($request, [
            'id' => ['required', 'integer'],
        ]);

        $input = $request->all();

        DB::table('identification_documents_name')
        ->where('id', $input['id'])
        ->delete();

        return response()->json(array('status' => 'ok'));
    }

    public function identification_documents_name__save(Request $request)
    {
        $validator = $this->validate($request, [
            'id' => ['required', 'integer'],
            'name' => ['required', 'unique:identification_documents_name,name,'.$request->id, 'max:255'],
        ]);

        $input = $request->all();

        $permission = DB::table('identification_documents_name')
              ->where('id', $input['id'])
              ->update(['name' => $input['name']]);

        return response()->json(array('ok' => 'ok'));
    }

    public function identification_documents_name__add(Request $request)
    {
        $validator = $this->validate($request, [
            'name' => ['required', 'unique:identification_documents_name', 'max:255'],
        ]);

        $input = $request->all();

        $id = DB::table('identification_documents_name')->insertGetId(
            ['name' => $input['name'], 'created_at' => now(), 'updated_at' => now()],
        );

        return response()->json(array('ok' => 'ok', 'new_id' => $id));
    }

    ///
    ///
    ///
    
    public function document_on_education_name__get()
    {
        $list = DB::table('document_on_education_name')->select('id', 'name')->orderBy('id','DESC')->get();

        return $list;
    }

    public function document_on_education_name__delete(Request $request)
    {
        $validator = $this->validate($request, [
            'id' => ['required', 'integer'],
        ]);

        $input = $request->all();

        DB::table('document_on_education_name')
        ->where('id', $input['id'])
        ->delete();

        return response()->json(array('status' => 'ok'));
    }

    public function document_on_education_name__save(Request $request)
    {
        $validator = $this->validate($request, [
            'id' => ['required', 'integer'],
            'name' => ['required', 'unique:document_on_education_name,name,'.$request->id, 'max:255'],
        ]);

        $input = $request->all();

        $permission = DB::table('document_on_education_name')
              ->where('id', $input['id'])
              ->update(['name' => $input['name']]);

        return response()->json(array('ok' => 'ok'));
    }

    public function document_on_education_name__add(Request $request)
    {
        $validator = $this->validate($request, [
            'name' => ['required', 'unique:document_on_education_name', 'max:255'],
        ]);

        $input = $request->all();

        $id = DB::table('document_on_education_name')->insertGetId(
            ['name' => $input['name'], 'created_at' => now(), 'updated_at' => now()],
        );

        return response()->json(array('ok' => 'ok', 'new_id' => $id));
    }

    ///
    ///
    ///
    
    public function additional_marks__get()
    {
        $list = DB::table('document_on_education_name')->select('id', 'name')->orderBy('id','DESC')->get();

        return $list;
    }

    public function additional_marks__delete(Request $request)
    {
        $validator = $this->validate($request, [
            'id' => ['required', 'integer'],
        ]);

        $input = $request->all();

        DB::table('document_on_education_name')
        ->where('id', $input['id'])
        ->delete();

        return response()->json(array('status' => 'ok'));
    }

    public function additional_marks__save(Request $request)
    {
        $validator = $this->validate($request, [
            'id' => ['required', 'integer'],
            'name' => ['required', 'unique:document_on_education_name,name,'.$request->id, 'max:255'],
        ]);

        $input = $request->all();

        $permission = DB::table('document_on_education_name')
              ->where('id', $input['id'])
              ->update(['name' => $input['name']]);

        return response()->json(array('ok' => 'ok'));
    }

    public function additional_marks__add(Request $request)
    {
        $validator = $this->validate($request, [
            'name' => ['required', 'unique:document_on_education_name', 'max:255'],
        ]);

        $input = $request->all();

        $id = DB::table('document_on_education_name')->insertGetId(
            ['name' => $input['name'], 'created_at' => now(), 'updated_at' => now()],
        );

        return response()->json(array('ok' => 'ok', 'new_id' => $id));
    }


    ///
    ///
    ///
    
    public function stage_of_education__get()
    {
        $list = DB::table('stage_of_education')->select('id', 'name')->orderBy('id','DESC')->get();

        return $list;
    }

    public function get_stage_of_education_list(Request $request)
    {
        $stage_of_education = DB::table('stage_of_education')
            ->select('stage_of_education.id as id', 'stage_of_education.name as name')
            ->get();

        return response()->json($stage_of_education);
    }

    public function stage_of_education__delete(Request $request)
    {
        $validator = $this->validate($request, [
            'id' => ['required', 'integer'],
        ]);

        $input = $request->all();

        DB::table('stage_of_education')
        ->where('id', $input['id'])
        ->delete();

        return response()->json(array('status' => 'ok'));
    }

    public function stage_of_education__save(Request $request)
    {
        $validator = $this->validate($request, [
            'id' => ['required', 'integer'],
            'name' => ['required', 'unique:stage_of_education,name,'.$request->id, 'max:255'],
        ]);

        $input = $request->all();

        $permission = DB::table('stage_of_education')
              ->where('id', $input['id'])
              ->update(['name' => $input['name']]);

        return response()->json(array('ok' => 'ok'));
    }

    public function stage_of_education__add(Request $request)
    {
        $validator = $this->validate($request, [
            'name' => ['required', 'unique:stage_of_education', 'max:255'],
        ]);

        $input = $request->all();

        $id = DB::table('stage_of_education')->insertGetId(
            ['name' => $input['name'], 'created_at' => now(), 'updated_at' => now()],
        );

        return response()->json(array('ok' => 'ok', 'new_id' => $id));
    }


}