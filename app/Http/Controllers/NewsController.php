<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\News;
use App\NewsCategories;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use DB;
use Auth;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $validator = $this->validate($request, [
            'category' => ['integer'],
        ]);

        $input = $request->all();

        if ($request->filled('category')) {

            return News::leftjoin('users', 'news.author', '=', 'users.id')
            ->leftjoin('news_categories', 'news_categories.id', '=', 'news.category')
            ->select('news.*', 'users.name as author_name', 'news_categories.name as name_categories')
            ->where('category', $input['category'])
            ->where('status', 1)
            ->paginate(15);

        } else return News::leftjoin('users', 'news.author', '=', 'users.id')
            ->leftjoin('news_categories', 'news_categories.id', '=', 'news.category')
            ->select('news.*', 'users.name as author_name', 'news_categories.name as name_categories')
            ->where('status', 1)
            ->paginate(15);

    }

    public function getid(Request $request)
    {
        $validator = $this->validate($request, [
            'id' => ['required', 'integer'],
        ]);

        $input = $request->all();
        
        $news = News::where('id', $input['id'])
            ->where('status', 1)->first();

        News::where('id', $input['id'])->increment('views');

        return $news;

    }

    public function getcategories(Request $request)
    {
        return NewsCategories::get();
    }
}