<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Auth::routes();

Route::group(['middleware' => 'auth'], function(){
	Route::get('/', function(){
		return redirect('/bands');
	});

	Route::get('/home', function(){
		return redirect('/bands');
	});

	Route::resource('/bands', 'BandController');

	Route::resource('/albums', 'AlbumController');
});

