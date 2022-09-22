<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Articles;
use App\ArticlesCategories;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use DB;
use Auth;

class ArticlesController extends Controller
{
    public function index(Request $request)
    {
        $validator = $this->validate($request, [
            'category' => ['integer'],
        ]);

        $input = $request->all();

        if ($request->filled('category')) {

            return Articles::leftjoin('users', 'articles.author', '=', 'users.id')
            ->leftjoin('articles_categories', 'articles_categories.id', '=', 'articles.category')
            ->select('articles.*', 'users.name as author_name', 'articles_categories.name as name_categories')
            ->where('category', $input['category'])
            ->where('status', 1)
            ->paginate(15);

        } else return Articles::leftjoin('users', 'articles.author', '=', 'users.id')
            ->leftjoin('articles_categories', 'articles_categories.id', '=', 'articles.category')
            ->select('articles.*', 'users.name as author_name', 'articles_categories.name as name_categories')
            ->where('status', 1)
            ->paginate(15);

    }

    public function getid(Request $request)
    {
        $validator = $this->validate($request, [
            'id' => ['required', 'integer'],
        ]);

        $input = $request->all();
        
        $articles = Articles::where('id', $input['id'])
            ->where('status', 1)->first();

        Articles::where('id', $input['id'])->increment('views');

        return $articles;

    }

    public function getcategories(Request $request)
    {
        return ArticlesCategories::get();
    }

    public function regpage(Request $request)
    {

        return Articles::select('articles.*')
            ->where('status', 1)
            ->limit(2)
            ->get();
    }
}