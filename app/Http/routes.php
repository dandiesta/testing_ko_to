<?php

Route::post('/login', ['as' => 'login', 'uses' => 'AuthController@login']);
Route::post('/authenticate', ['as' => 'authenticate', 'uses' => 'AuthController@authenticate']);

$router->group(['middleware' => 'auth'], function() {

    // Apps
    Route::get('/',                 ['as' => 'top_apps',       'uses' => 'ApplicationController@top_apps']);
    Route::get('/app',              ['as' => 'app',            'uses' => 'ApplicationController@index']);
    Route::get('/myapps/own',       ['as' => 'my_apps',        'uses' => 'ApplicationController@my_apps']);
    Route::get('/myapps/installed', ['as' => 'installed_apps', 'uses' => 'ApplicationController@installed_apps']);
    Route::get('/top_apps', ['as' => 'top_apps', 'uses' => 'ApplicationController@top_apps']);
    Route::get('/app/comment', ['as' => 'comment_app', 'uses' => 'ApplicationController@comment']);
    Route::post('/app/post_comment', ['as' => 'post_comment', 'uses' => 'ApplicationController@postComment']);
    Route::get('/app/new', ['as' => 'new_app', 'uses' => 'ApplicationController@newApp']);
    Route::get('/app/preferences', ['as' => 'preferences', 'uses' => 'ApplicationController@preferences']);

    // Packages
    Route::get('/package', ['as' => 'package', 'uses' => 'PackageController@index' ]);
    Route::get('/package/edit', ['as' => 'edit_package', 'uses' => 'PackageController@edit' ]);
    Route::get('/package/delete_confirm', ['as' => 'delete_confirm', 'uses' => 'PackageController@delete_confirm']);
    Route::post('/package/delete', ['as' => 'delete_package', 'uses' => 'PackageController@delete']);

    Route::get('/doc/{page}', ['as' => 'docs', 'uses' => 'ApplicationController@documentation']);

    Route::get('/logout', ['as' => 'logout', 'uses' => 'AuthController@logout']);

});

Route::controllers([
    'auth' => 'Auth\AuthController',
    'password' => 'Auth\PasswordController',
]);
