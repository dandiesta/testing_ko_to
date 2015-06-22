<?php

Route::get('/asdf', ['as' => 'login', 'uses' => 'AuthController@login']);
Route::get('home', 'HomeController@index');

Route::post('/authenticate', ['as' => 'authenticate', 'uses' => 'AuthController@authenticate']);
Route::get('/logout', ['as' => 'logout', 'uses' => 'AuthController@logout']);

// packages
Route::get('/package', ['as' => 'package', 'uses' => 'PackageController@index' ]); //dapat may id to
Route::get('/package/edit', ['as' => 'edit_package', 'uses' => 'PackageController@edit' ]);


Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);
