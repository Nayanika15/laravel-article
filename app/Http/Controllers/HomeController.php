<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;

use Illuminate\Http\Request;
use App\Notifications\Test;
use Auth;
use Notification;
   
class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    { 
        return view('site.wordify.home')->with(['latest_articles' => Article::latestArticle()->Paginate(env('PAGINATE_LIMIT', 4)), 'featured_articles' =>Article::featuredArticles()]);
    }

    //to test notification
    public function push()
    {
	    Notification::send(User::all(),new Test);
	    return redirect()->back();
	}
}