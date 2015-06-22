<?php

Route::get('/login', ['as' => 'login', 'uses' => 'AuthController@login']);
Route::post('/authenticate', ['as' => 'authenticate', 'uses' => 'AuthController@authenticate']);

$router->group(['middleware' => 'auth'], function() {

    // Apps
    Route::get('/',                 ['as' => 'top_apps',       'uses' => 'ApplicationController@top_apps']);
    Route::get('/app',              ['as' => 'app',            'uses' => 'ApplicationController@index']);
    Route::get('/myapps/own',       ['as' => 'my_apps',        'uses' => 'ApplicationController@my_apps']);
    Route::get('/myapps/installed', ['as' => 'installed_apps', 'uses' => 'ApplicationController@installed_apps']);
    Route::get('/top_apps', ['as' => 'top_apps', 'uses' => 'ApplicationController@top_apps']);

    // Packages
    Route::get('/package', ['as' => 'package', 'uses' => 'ApplicationController@packages' ]); //dapat may id to
    Route::get('/package/edit', ['as' => 'edit_package', 'uses' => 'PackageController@edit' ]);

    Route::get('/doc/{page}', ['as' => 'docs', 'uses' => 'ApplicationController@documentation']);

    Route::get('/logout', ['as' => 'logout', 'uses' => 'AuthController@logout']);

});

Route::controllers([
    'auth' => 'Auth\AuthController',
    'password' => 'Auth\PasswordController',
]);
