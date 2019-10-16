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

Route::get ('/callback/{service}', 'UserController@callback');
Route::get ('/redirect/{service}', 'UserController@redirect');
Route::get('add-mobile', 'UserController@update')->name('add-phone');
Route::post('add-mobile', 'UserController@updateMobile')->name('update-phone');

Route::group(['middleware' => ['auth', 'check-mobile']], function()
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
	Route::get('payment', 'ArticleController@makePayment')->name('make-payment');
	Route::post('payment', 'ArticleController@doPayment')->name('do-payment');
	Route::get('payment/success', 'ArticleController@succesful')->name('successful-payment');
	
	Route::group(['middleware' => 'role'], function()
	{	
		Route::get('article/{id}', 'ArticleController@featured')->name('feature-article');

		Route::prefix('admin')->group(function () 
		{
			Route::get('view-category', 'CategoryController@index')->name('view-category');
			Route::get('add-category', 'CategoryController@create')->name('add-category');
			Route::post('add-category', 'CategoryController@store');
			Route::get('add-category/{id}', 'CategoryController@create')->name('edit-category');
			Route::post('add-category/{id}', 'CategoryController@store');
			Route::get('delete-category/{id}', 'CategoryController@destroy')->name('destroy-category');
			Route::get('comment/list', 'CommentController@list')->name('all-comments');
			Route::get('comment/approve-comment/{id}', 'CommentController@approve')->name('approve-comment');
		});
	});
		
});



Route::group(['middleware' => 'guest'], function () {
    Route::get('login', 'UserController@index')->name('login');
	Route::post('login', 'UserController@login')->name('dologin');
	Route::get('register', 'UserController@create')->name('do-registration');
	Route::post('register',  'UserController@register')->name('register');
});
Route::get('verify-mobile/{mobile}',  'MobileVerificationController@SendCode')->name('verify-mobile');

Route::post('comment/add/{id}', 'CommentController@store')->name('add-comment');
Route::get('article/{slug}', 'ArticleController@detail')->name('detail-article');
Route::get('category/{slug}', 'CategoryController@detail')->name('detail-category');

Route::get ('terms', 'HomeController@index');
Route::get ('policy', 'HomeController@index');