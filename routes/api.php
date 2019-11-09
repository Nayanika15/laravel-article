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
	Route::get('latest', 'Api\ArticleController@latest');
	Route::get('{slug}', 'Api\ArticleController@detail');
});

Route::prefix('category')->group(function () 
{	
	Route :: post('add', 'Api\CategoryController@add');
	Route::get('active', 'Api\CategoryController@activeCategories');
	Route::get('{slug}', 'Api\CategoryController@detail');
});

Route::get('sideBar', 'Api\HomeController@sideBar');
Route::post('feedback', 'Api\FeedbackController@add')->middleware('auth:api');
Route::get('verify-mobile/{mobile}',  'Api\FeedbackController@verifyMobile');