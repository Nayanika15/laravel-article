<?php
namespace App\Http\Composer;

use Illuminate\View\View;
use App\Models\Article;

class PopularArticleComposer
{
    /**
     * The user repository implementation.
     *
     * @var UserRepository
     */
    protected $article;

    /**
     * Create a new article composer.
     *
     * @param  UserRepository  $users
     * @return void
     */
    public function __construct(Article $article)
    {
        // Dependencies automatically resolved by service container...
        $this->article = $article;
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {   
        $popular_articles = $this->article->popular();
        $view->with('popular_articles', $popular_articles);
    }
}