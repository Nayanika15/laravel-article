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
Route::post('reset-password', 'Api\UserController@updatePassword');
Route::group(['middleware' => 'auth:api'], function(){
	Route::post('details', 'Api\UserController@details');
});

Route::any('sociallogin/{provider}', 'Api\UserController@SocialSignup');
Route::get('callback/google', 'HomeController@index');

Route::prefix('article')->group(function () 
{	
	Route::get('featured', 'Api\ArticleController@featuredArticles');
	Route::get('latest', 'Api\ArticleController@latest');
	Route::post('add', 'Api\ArticleController@add')->middleware('auth:api');
	Route::get('edit/{id}', 'Api\ArticleController@editArticle');
	Route::put('update/{id}', 'Api\ArticleController@update');
	Route::get('delete/{id}', 'Api\ArticleController@delete');
	Route::get('feature/{id}', 'Api\ArticleController@featured');
	Route::any('list', 'Api\ArticleController@list');
	Route::get('{slug}', 'Api\ArticleController@detail');
});

Route::prefix('category')->group(function () 
{
	Route::post('add', 'Api\CategoryController@add');
	Route::get('delete/{id}', 'Api\CategoryController@delete');
	Route::get('edit/{id}', 'Api\CategoryController@editCategory');
	Route::put('update/{id}', 'Api\CategoryController@update');
	Route::any('list', 'Api\CategoryController@list');
	Route::get('active', 'Api\CategoryController@activeCategories');
	Route::get('{slug}', 'Api\CategoryController@detail');
});
Route::prefix('comment')->group(function () 
{
	Route::get('list', 'Api\CommentController@list');
	Route::get('approve/{id}', 'Api\CommentController@approve');
	Route::post('add/{id}', 'Api\CommentController@store');
});

Route::post('doPayment', 'Api\ArticleController@doPayment')->middleware('auth:api');
Route::get('sideBar', 'Api\HomeController@sideBar');
Route::post('feedback', 'Api\FeedbackController@add');//->middleware('auth:api');
Route::get('verify-mobile/{mobile}',  'Api\FeedbackController@verifyMobile');
//store a push subscriber.
Route::post('push','Api\PushController@store')->middleware('auth:api');