<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArticleRequest;

use App\Models\Article;
use App\Models\Category;
use App\Models\ArticleCategories;

class ArticleController extends Controller
{   
    protected $article;

   /* protected function __construct(Article $article)
    {
        return $this->article;
    }*/
    
    /**
     * Show the list of all articles.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {    
        if(request()->ajax())
        {   
            $articles = new Article;
            $data = $articles->allArticles();
            return $data;
        }

        return view('site.wordify.articles.list');
    }
    
    /**
     * Show the list of all articles.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create($id=0)
    {   
        
        $article = Article::find($id);
        $all_categories = Category::select('id', 'name')->pluck('name', 'id')->toArray();

        if ($id != 0 && empty($article))
        {   
            return redirect()->route('all-articles')->with('ErrorMessage', 'Category was not found.');
        }
        else
        {   
            $sel_categories = ($id==0) ? array() : $article->categories->pluck('id')->toArray();
            return view('site.wordify.articles.add')->with(['categories' => $all_categories, 'article' => $article, 'selected' => $sel_categories]);
        }
        
    }

     /**
     * Store or update article details to the database.
     *
     * @param \App\Http\Requests\CategoryRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(ArticleRequest $request, $id=0) 
    {   
        $article = new Article;
        $result = $article->addUpdateArticle($request, $id);

        if($result['errFlag'] == 0)
        {   
            return redirect()->route($result['route'])
                        ->with('success', $result['msg']);
        }
        else
        {
            return redirect()->route($result['route'])
                    ->with('ErrorMessage', $result['msg'])
                    ->withInput();
        }
    }

    /**
     * Delete article record.
     *
     * @param int $id
     * @return  \Illuminate\Http\Response
     */
    public function destroy($id)
    {  
        $article = new Article;
        $result = $article->deleteArticle($id);
        if($result)
        {
            return redirect()->route($result['route'])
                ->with($result['msgType'], $result['msg']);
        }
        else
        {
            return redirect()->route('all-articles')
                ->with('ErrorMessage', 'There is some error.');
        }
        
    }

      /**
     * Show the detail of article.
     * @param string $slug
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function detail($slug)
    {   
        $article = new Article;
        $category = new Category;
        $data = array();
        $data['popular_articles'] = $article->popular();
        $data['active_categories'] = $category->activeCategories();
        $data['article'] = $article->articleDetail($slug);
        if(!empty($data['article']))
        {
            return view('site.wordify.articles.detail')->with('data', $data); 
        }
        else
        {
            return redirect()->route('homepage');
        }
              
    }
    
}
