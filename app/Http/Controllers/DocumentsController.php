<?php

namespace App\Http\Controllers;

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

    public function getcategories(Request $request)
    {
        return DocumentsCategories::get();
    }
}