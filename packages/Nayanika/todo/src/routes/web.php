<?php
Route::group(['middleware' => ['web', 'auth'], 'prefix' => 'todo'], function()
{
	Route::get('list', 'Nayanika\Todo\app\http\Controllers\TodoController@list')
		->name('Tasks');
	Route::post('add', 'Nayanika\Todo\app\http\Controllers\TodoController@store')
		->name('store-task');
	Route::get('edit/{id}', 'Nayanika\Todo\app\http\Controllers\TodoController@edit')
		->name('edit-task');
	Route::patch('edit/{id}', 'Nayanika\Todo\app\http\Controllers\TodoController@update')
		->name('update-task');
	Route::get('status/{id}', 'Nayanika\Todo\app\http\Controllers\TodoController@updateStatus')->name('update-status');
	Route::get('delete/{id}', 'Nayanika\Todo\app\http\Controllers\TodoController@destroy')->name('delete-task');
});
?>