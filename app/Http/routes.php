<?php

Route::get('/login', ['as' => 'login', 'uses' => 'AuthController@login']);
Route::post('/authenticate', ['as' => 'authenticate', 'uses' => 'AuthController@authenticate']);

$router->group(['middleware' => 'auth'], function() {

    // Apps
    Route::get('/',                 ['as' => 'top_apps',       'uses' => 'ApplicationController@top_apps']); //CAMEL CASE
    Route::get('/app',              ['as' => 'app',            'uses' => 'ApplicationController@index']); //CAMEL CASE
    Route::get('/myapps/own',       ['as' => 'my_apps',        'uses' => 'ApplicationController@my_apps']); //CAMEL CASE
    Route::get('/myapps/installed', ['as' => 'installed_apps', 'uses' => 'ApplicationController@installed_apps']); //CAMEL CASE
    Route::get('/top_apps',         ['as' => 'top_apps',       'uses' => 'ApplicationController@top_apps']); //CAMEL CASE

    // Packages
    Route::get('/package',           ['as' => 'package',      'uses' => 'PackageController@index' ]);
    Route::get('/package/edit',      ['as' => 'edit_package', 'uses' => 'PackageController@edit']);

    Route::post('/package/edit/save', ['as' => 'save_package', 'uses' => 'PackageController@saveEdit']);


    Route::get('/logout', ['as' => 'logout', 'uses' => 'AuthController@logout']);

});

Route::controllers([
    'auth' => 'Auth\AuthController',
    'password' => 'Auth\PasswordController',
]);
