<?php

Route::get('/asdf', ['as' => 'login', 'uses' => 'AuthController@login']);
Route::get('home', 'HomeController@index');

Route::post('/authenticate', ['as' => 'authenticate', 'uses' => 'AuthController@authenticate']);

// packages
Route::get('/package', ['as' => 'package', 'uses' => 'ApplicationController@packages' ]); //dapat may id to



Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);
