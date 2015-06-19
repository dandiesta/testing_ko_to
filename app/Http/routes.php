<?php

Route::get('/asdf', ['as' => 'login', 'uses' => 'AuthController@login']);
Route::get('home', 'HomeController@index');

Route::post('/authenticate', ['as' => 'authenticate', 'uses' => 'AuthController@authenticate']);
Route::get('/logout', ['as' => 'logout', 'uses' => 'AuthController@logout']);

Route::get('/myapps/own',    ['as' => 'my_apps',      'uses' => 'ApplicationController@my_apps']);
Route::get('/myapps/own',    ['as' => 'my_apps',      'uses' => 'ApplicationController@my_apps']);

Route::get('home', 'HomeController@index');

Route::get('/index', ['as' => 'index', 'uses' => 'ApplicationController@index']);

// packages
Route::get('/package', ['as' => 'package', 'uses' => 'ApplicationController@packages' ]); //dapat may id to

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);
