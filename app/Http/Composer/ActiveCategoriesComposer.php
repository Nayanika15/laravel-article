<?php
namespace App\Http\Composer;

use Illuminate\View\View;
use Cache;
use App\Models\Category;

class ActiveCategoriesComposer
{
    /**
     * The user repository implementation.
     *
     * @var UserRepository
     */
    protected $category;

    /**
     * Create a new article composer.
     *
     * @param  UserRepository  $users
     * @return void
     */
    public function __construct(Category $category)
    {
        // Dependencies automatically resolved by service container...
        $this->category = $category;
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {   
        $active_categories = $value = Cache::rememberForever('active_categories', function () {
            return Category::activeCategories();
        });
        $view->with('active_categories', $active_categories);
    }
}