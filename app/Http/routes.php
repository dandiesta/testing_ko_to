<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', ['as' => 'login', 'uses' => 'AuthController@login']);

Route::post('/authenticate', ['as' => 'authenticate', 'uses' => 'AuthController@authenticate']);
Route::get('/logout', ['as' => 'logout', 'uses' => 'AuthController@logout']);

Route::get('/index', ['as' => 'index', 'uses' => 'ApplicationController@index']);

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);
