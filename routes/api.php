<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', 'Api\UserController@login');
Route::post('register', 'Api\UserController@register');
Route::group(['middleware' => 'auth:api'], function(){
	Route::post('details', 'Api\UserController@details');
});

Route::prefix('article')->group(function () 
{	
	Route::get('featured', 'Api\ArticleController@featuredArticles');
	Route::get('latest', 'Api\ArticleController@latest');
	Route::post('add', 'Api\ArticleController@add')->middleware('auth:api');
	Route::any('list', 'Api\ArticleController@list');
	Route::get('{slug}', 'Api\ArticleController@detail');
	Route::get('feature/{id}', 'Api\ArticleController@feature');
});

Route::prefix('category')->group(function () 
{
	Route::post('add', 'Api\CategoryController@add');
	Route::get('edit/{id}', 'Api\CategoryController@editCategory');
	Route::put('update/{id}', 'Api\CategoryController@update');
	Route::any('list', 'Api\CategoryController@list');
	Route::get('active', 'Api\CategoryController@activeCategories');
	Route::get('{slug}', 'Api\CategoryController@detail');
});
Route::prefix('comment')->group(function () 
{
	Route::post('add/{id}', 'Api\CommentController@store');
	Route::get('list', 'Api\CommentController@list');
	Route::get('approve/{id}', 'Api\CommentController@approve');
});

Route::post('doPayment', 'Api\ArticleController@doPayment')->middleware('auth:api');;
Route::get('sideBar', 'Api\HomeController@sideBar');
Route::post('feedback', 'Api\FeedbackController@add');
Route::get('verify-mobile/{mobile}',  'Api\FeedbackController@verifyMobile');