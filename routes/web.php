<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', 'HomeController@index')->name('homepage');

Route::group(['middleware' => 'auth'], function()
{
	Route::get('dashboard', 'UserController@dashboard')->name('dashboard');
	Route::get('logout', 'UserController@logoutUser')->name('logout');
	Route::get('article/list', 'ArticleController@index')->name('all-articles');
	Route::get('article/view/{id}', 'ArticleController@show')->name('view-article');
	Route::get('article/add', 'ArticleController@create')->name('add-article');
	Route::post('article/add', 'ArticleController@store')->name('store-article');
	Route::get('article/add/{id}', 'ArticleController@create')->name('edit-article');
	Route::post('article/add/{id}', 'ArticleController@store')->name('update-article');
	Route::get('article/destroy/{id}', 'ArticleController@destroy')->name('delete-article');
	
	Route::group(['middleware' => 'role'], function()
	{
		Route::prefix('admin')->group(function () 
		{
			Route::get('view-category', 'CategoryController@index')->name('view-category');
			Route::get('add-category', 'CategoryController@create')->name('add-category');
			Route::post('add-category', 'CategoryController@store');
			Route::get('add-category/{id}', 'CategoryController@create')->name('edit-category');
			Route::post('add-category/{id}', 'CategoryController@store');
			Route::get('delete-category/{id}', 'CategoryController@destroy')->name('destroy-category');
		});
	});
		
});



Route::group(['middleware' => 'guest'], function () {
    Route::get('/login', 'UserController@index')->name('login');
	Route::post('/login', 'UserController@login')->name('dologin');
});
Route::get('article/{slug}', 'ArticleController@detail')->name('detail-article');

 