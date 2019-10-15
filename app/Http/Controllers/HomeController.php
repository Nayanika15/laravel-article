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
        $article = new Article;
        return view('site.wordify.home')->with('latest_articles', $article->latestArticle()->Paginate(env('PAGINATE_LIMIT', 4)));
    }
     
}
