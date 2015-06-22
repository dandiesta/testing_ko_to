<?php

Route::get('/login', ['as' => 'login', 'uses' => 'AuthController@login']);
Route::post('/authenticate', ['as' => 'authenticate', 'uses' => 'AuthController@authenticate']);
Route::get('/logout', ['as' => 'logout', 'uses' => 'AuthController@logout']);

Route::group(['middleware' => ['auth']], function()
{
    // apps
    Route::get('/',                 ['as' => 'top_apps',       'uses' => 'ApplicationController@top_apps']);
    Route::get('/app',              ['as' => 'app',            'uses' => 'ApplicationController@index']);
    Route::get('/myapps/own',       ['as' => 'my_apps',        'uses' => 'ApplicationController@my_apps']);
    Route::get('/myapps/installed', ['as' => 'installed_apps', 'uses' => 'ApplicationController@installed_apps']);

    // packages
    Route::get('/package', ['as' => 'package', 'uses' => 'ApplicationController@packages' ]); //dapat may id to
});

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);
