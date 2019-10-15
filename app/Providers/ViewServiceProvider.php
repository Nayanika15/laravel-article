<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
         // Using class based composers for related articles...
        View::composer(
            ['site.wordify.header', 'site.wordify.side-bar', 'site.wordify.footer', 'site.wordify.home'], 'App\Http\Composer\ActiveCategoriesComposer'
        );

        View::composer('site.wordify.side-bar', 'App\Http\Composer\PopularArticleComposer');
        View::composer(['site.wordify.footer'], 'App\Http\Composer\LatestArticleComposer');

    }
}
