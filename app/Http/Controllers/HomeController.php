<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\Category;

class HomeController extends Controller
{
    
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {   
        $popular_articles = Article::where('approve_status',1)->orderByDesc('created_at')->Paginate(env('PAGINATE',4));
        return view('site.wordify.home')->with('popular_articles',$popular_articles);
    }
     /**
     * Show the detail of article.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show($slug)
    {   
        $article = Article::select(['id', 'title', 'details', 'user_id', 'approve_status', 'created_at', 'updated_at'])->where('slug', $slug)->first();
        return view('site.wordify.articles.detail')->with('article', $article);
       
    }
}
