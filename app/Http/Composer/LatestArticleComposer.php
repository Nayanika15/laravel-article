<?php
namespace App\Http\Composer;

use Illuminate\View\View;
use App\Models\Article;

class LatestArticleComposer
{
    /**
     * The user repository implementation.
     *
     * @var article
     */
    protected $article;

    /**
     * Create a new article composer.
     *
     * @param  article $article
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
        $latest_articles = $this->article->latestArticle()->limit(3)->get();
        $view->with('latest_articles', $latest_articles);
    }
}