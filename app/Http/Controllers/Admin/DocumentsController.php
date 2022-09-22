<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Documents;
use App\DocumentsCategories;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use DB;
use Auth;

class DocumentsController extends Controller
{

    function __construct()
    {
        $this->middleware(['role_or_permission:администратор|директор|просмотр документов']);
    }

    public function index(Request $request)
    {
        $validator = $this->validate($request, [
            'category' => ['integer'],
        ]);

        $input = $request->all();

        if ($request->filled('category')) {
            return Documents::where('category', $input['category'])->paginate(15);
        } else return Documents::paginate(15);

    }

    private function quickRandom($length = 16)
    {
        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        return substr(str_shuffle(str_repeat($pool, 5)), 0, $length);
    }

    public function add(Request $request)
    {
        $validator = $this->validate($request, [
            'category_select' => ['required', 'integer'],
            'name' => ['required', 'unique:documents', 'max:255'],
            'description' => ['nullable'],
            'file' => ['required', 'mimes:jpeg,jpg,pdf', 'max:10000'],
        ]);

        $input = $request->all();

        if ($request->hasFile('file'))
        {
            if ($request->file('file')->isValid()) {

                $fileName = $this->quickRandom(8).'.documentsfiles.'.$request->file->extension();
                $file_ = $request->file('file')->storeAs('uploads', $fileName, 'public');
        
            } 
        }

        if ($request->file('file')->isValid()) {

            $id = DB::table('documents')->insertGetId(
            ['name' => $input['name'], 'category' => $input['category_select'], 'description' => $input['description'], 'file_url' => $file_, 'type' => $request->file->extension(), 'user_id' => Auth::id(), 'created_at' => now(), 'updated_at' => now()],
            );
        }

        return response()->json(array('ok' => 'ok', 'new_id' => $id));
    }

    public function destroy(Request $request)
    {
         $validator = $this->validate($request, [
            'id' => ['required', 'integer'],
        ]);

        $input = $request->all();

        $document = DB::table('documents')
           ->where('documents.id', $input['id'])
           ->select('documents.id', 'documents.file_url')
           ->first();

        if($document) {

            if($document->file_url){
                Storage::disk('public')->delete($document->file_url);
            }

            DB::table('documents')
           ->where('id', $input['id'])
           ->delete();
        }

       return response()->json(array('status' => 'ok'));
    }

    public function categories__get()
    {
        $list = DB::table('documents_categories')->select('id', 'name')->orderBy('id','DESC')->get();

        return $list;
    }

    public function categories__delete(Request $request)
    {
        $validator = $this->validate($request, [
            'id' => ['required', 'integer'],
        ]);

        $input = $request->all();

        DB::table('documents_categories')
        ->where('id', $input['id'])
        ->delete();

        return response()->json(array('status' => 'ok'));
    }

    public function categories__save(Request $request)
    {
        $validator = $this->validate($request, [
            'id' => ['required', 'integer'],
            'name' => ['required', 'unique:documents_categories,name,'.$request->id, 'max:255'],
        ]);

        $input = $request->all();

        $permission = DB::table('documents_categories')
              ->where('id', $input['id'])
              ->update(['name' => $input['name']]);

        return response()->json(array('ok' => 'ok'));
    }

    public function categories__add(Request $request)
    {
        $validator = $this->validate($request, [
            'name' => ['required', 'unique:documents_categories', 'max:255'],
        ]);

        $input = $request->all();

        $id = DB::table('documents_categories')->insertGetId(
            ['name' => $input['name'], 'created_at' => now(), 'updated_at' => now()],
        );

        return response()->json(array('ok' => 'ok', 'new_id' => $id));
    }

    public function get_file(Request $request)
    {

        $validator = $this->validate($request, [
            'id' => ['required', 'integer'],
        ]);

        $input = $request->all();

        $document = Documents::where('id', $input['id'])
        ->select('file_url', 'name', 'type')
        ->first();

        if($document['file_url']){
            return Storage::disk('public')->download($document['file_url'], $document['name'].'.'.$document['type']);
        } else abort('404');

    }

}
