<?php
namespace Nayanika\Todo;
use Illuminate\Support\ServiceProvider;

class TodoServiceProvider extends ServiceProvider {
    public function boot()
    {	
    	$this->loadRoutesFrom(__DIR__.'/routes/web.php');
    	$this->loadMigrationsFrom(__DIR__.'/Database/migrations');
    	$this->loadViewsFrom(__DIR__.'/resources/views', 'todo');
    }
    public function register()
    {
    	$this->app->make('Nayanika\Todo\App\Http\Controllers\TodoController');
    }
}
?>