<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\News;
use App\NewsCategories;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Traits\UploadTrait;
use Illuminate\Support\Facades\Storage;
use DB;
use Auth;

class NewsController extends Controller
{
    use UploadTrait;

    function __construct()
    {
        $this->middleware(['role_or_permission:администратор|директор|новости']);
    }

    public function index(Request $request)
    {
        $validator = $this->validate($request, [
            'category' => ['integer'],
        ]);

        $input = $request->all();

        if ($request->filled('category')) {
            return News::leftjoin('users', 'news.author', '=', 'users.id')->select('news.*', 'users.name as author_name')->where('category', $input['category'])->paginate(15);
        } else return News::leftjoin('users', 'news.author', '=', 'users.id')->select('news.*', 'users.name as author_name')->paginate(15);

    }

    public function getid(Request $request)
    {
        $validator = $this->validate($request, [
            'id' => ['required', 'integer'],
        ]);

        $input = $request->all();
        
        $news = News::where('id', $input['id'])->first();
        
        return $news;

    }

    public function getcategories(Request $request)
    {
        return NewsCategories::get();
    }

    public function editor_upload_img(Request $request)
    {
        $request->validate([
            'image'     =>  'required|image|mimes:jpeg,png,jpg,gif|max:8192'
        ]);

        if ($request->has('image')) {

            $image = $request->file('image');

            $name = Str::slug(sha1($request->input('image').time()));
            // Define folder path
            $folder = 'news/images/';
            // Make a file path where image will be stored [ folder path + file name + file extension]
            $filePath = $folder . $name. '.' . $image->getClientOriginalExtension();
            // Upload image
            // 
            $path = $request->file('image')->storeAs('public/', $filePath);
        }

        return  array('success' => 1, 'file' => array('url' => Storage::url($filePath)));
    }

    public function editor_upload_file(Request $request)
    {
        $request->validate([
            'file'     =>  'required|file|mimes:jpeg,png,jpg,gif,doc,docx,pdf,xlsx,xls|max:8192'
        ]);

        if ($request->has('file')) {

            $file_upload = $request->file('file');

            $name = Str::slug(sha1($request->input('file').time()));
            // Define folder path
            $folder = 'news/files/';
            // Make a file path where image will be stored [ folder path + file name + file extension]
            $filePath = $folder . $name. '.' . $file_upload->getClientOriginalExtension();
            // Upload image
            // 
            $path = $request->file('file')->storeAs('public/', $filePath);

            $size = Storage::size($path);
        }

        return  array(
            'success' => 1, 
            'file'  => array(
                'url' => Storage::url($filePath), 
                'size' => $size, 
                'name' => $file_upload->extension().$file_upload->getClientOriginalName(), 
                'extension' => $file_upload->extension()
            )
        );
    }

    public function add(Request $request)
    {
        $validator = $this->validate($request->merge([
           'text' => json_encode($request->text)
        ]), [
            'category_select' => ['required', 'integer'],
            'status' => ['required', 'integer'],
            'name' => ['required', 'unique:news', 'max:255'],
            'text' => ['required', 'json'],
        ]);

        $input = $request->all();

        $id = DB::table('news')->insertGetId(
            ['name' => $input['name'], 'category' => $input['category_select'], 'status' => $input['status'], 'text' => $input['text'], 'views' => 0, 'author' => Auth::id(), 'created_at' => now(), 'updated_at' => now()],
        );

        return response()->json(array('ok' => 'ok', 'new_id' => $id));
    }

    public function edit(Request $request)
    {
        $validator = $this->validate($request->merge([
           'text' => json_encode($request->text)
        ]), [
            'id' => ['required', 'integer'],
            'category' => ['required', 'integer'],
            'status' => ['required', 'integer'],
            'name' => ['required', 'unique:news,name,'.$request->id],
            'text' => ['required', 'json'],
        ]);

        $input = $request->all();

        DB::table('news')->where('id', $input['id'])->update(
            ['name' => $input['name'], 'category' => $input['category'], 'status' => $input['status'], 'text' => $input['text'], 'updated_at' => now()],
        );

        return response()->json(array('ok' => 'ok'));
    }

    public function destroy(Request $request)
    {
        $validator = $this->validate($request, [
            'id' => ['required', 'integer'],
        ]);

        $input = $request->all();

        DB::table('news')
        ->where('id', $input['id'])
        ->delete();

        return response()->json(array('status' => 'ok'));
    }

    public function categories__get()
    {
        $list = DB::table('news_categories')->select('id', 'name')->orderBy('id','DESC')->get();

        return $list;
    }

    public function categories__delete(Request $request)
    {
        $validator = $this->validate($request, [
            'id' => ['required', 'integer'],
        ]);

        $input = $request->all();

        DB::table('news_categories')
        ->where('id', $input['id'])
        ->delete();

        return response()->json(array('status' => 'ok'));
    }

    public function categories__save(Request $request)
    {
        $validator = $this->validate($request, [
            'id' => ['required', 'integer'],
            'name' => ['required', 'unique:news_categories,name,'.$request->id, 'max:255'],
        ]);

        $input = $request->all();

        $permission = DB::table('news_categories')
              ->where('id', $input['id'])
              ->update(['name' => $input['name']]);

        return response()->json(array('ok' => 'ok'));
    }

    public function categories__add(Request $request)
    {
        $validator = $this->validate($request, [
            'name' => ['required', 'unique:news_categories', 'max:255'],
        ]);

        $input = $request->all();

        $id = DB::table('news_categories')->insertGetId(
            ['name' => $input['name'], 'created_at' => now(), 'updated_at' => now()],
        );

        return response()->json(array('ok' => 'ok', 'new_id' => $id));
    }

}
