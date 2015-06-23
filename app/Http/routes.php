<?php

Route::post('/login', ['as' => 'login', 'uses' => 'AuthController@login']);
Route::post('/authenticate', ['as' => 'authenticate', 'uses' => 'AuthController@authenticate']);

$router->group(['middleware' => 'auth'], function() {

    // Apps
    Route::get('/',                 ['as' => 'top_apps',       'uses' => 'ApplicationController@top_apps']);
    Route::get('/top_apps',         ['as' => 'top_apps',       'uses' => 'ApplicationController@top_apps']);

    Route::get('/app',              ['as' => 'app',            'uses' => 'ApplicationController@index']);
    Route::get('/app/comment',      ['as' => 'comment_app',    'uses' => 'ApplicationController@comment']);
    Route::get('/app/new',          ['as' => 'new_app',        'uses' => 'ApplicationController@newApp']);
    Route::get('/app/preferences',  ['as' => 'preferences',    'uses' => 'ApplicationController@preferences']);

    Route::post('/app/post_comment',['as' => 'post_comment',   'uses' => 'ApplicationController@postComment']);

    // My Apps
    Route::get('/myapps/own',       ['as' => 'my_apps',        'uses' => 'ApplicationController@my_apps']);
    Route::get('/myapps/installed', ['as' => 'installed_apps', 'uses' => 'ApplicationController@installed_apps']);

    // Packages
    Route::get('/app/upload',             ['as' => 'upload_package', 'uses' => 'PackageController@upload']);
    Route::get('/package',                ['as' => 'package',        'uses' => 'PackageController@index' ]);
    Route::get('/package/edit',           ['as' => 'edit_package',   'uses' => 'PackageController@edit' ]);
    Route::get('/package/delete_confirm', ['as' => 'delete_confirm', 'uses' => 'PackageController@delete_confirm']);

    Route::post('/package/delete', ['as' => 'delete_package', 'uses' => 'PackageController@delete']);
    Route::get('/package/install', ['as' => 'install_package', 'uses' => 'PackageController@install']);
    Route::get('/package/install_plist', ['as' => 'install_plist', 'uses' => 'PackageController@install_plist']);
    Route::post('/package/edit/save',     ['as' => 'save_package',   'uses' => 'PackageController@saveEdit']);
    Route::post('/package/delete',        ['as' => 'delete_package', 'uses' => 'PackageController@delete']);

    // Docs
    Route::get('/doc/{page}', ['as' => 'docs', 'uses' => 'ApplicationController@documentation']);

    // Logout
    Route::get('/logout', ['as' => 'logout', 'uses' => 'AuthController@logout']);

});

Route::controllers([
    'auth' => 'Auth\AuthController',
    'password' => 'Auth\PasswordController',
]);
