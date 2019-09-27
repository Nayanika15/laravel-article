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
        $category = new category;
        $data = array();
        $data['popular_articles'] = $article->popular();
        $data['active_categories'] = $category->activeCategories();
        $data['latest_articles'] = Article::where('approve_status', '1')->latest()->Paginate(env('PAGINATE_LIMIT', 4));
        return view('site.wordify.home')->with('data', $data);
    }
     
}
