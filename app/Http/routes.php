<?php

Route::get('/login', ['as' => 'login', 'uses' => 'AuthController@login']);
Route::post('/authenticate', ['as' => 'authenticate', 'uses' => 'AuthController@authenticate']);

$router->group(['middleware' => 'auth'], function() {

    // Apps
    Route::get('/',                 ['as' => 'top_apps',       'uses' => 'ApplicationController@top_apps']);
    Route::get('/top_apps',         ['as' => 'top_apps',       'uses' => 'ApplicationController@top_apps']);

    Route::get('/app',              ['as' => 'app',            'uses' => 'ApplicationController@index']);
    Route::get('/app/comment',      ['as' => 'comment_app',    'uses' => 'ApplicationController@comment']);
    Route::get('/app/new',          ['as' => 'new_app',        'uses' => 'ApplicationController@newApp']);
    Route::get('/app/preferences',  ['as' => 'preferences',    'uses' => 'ApplicationController@preferences']);

    Route::post('/app/post_comment',       ['as' => 'post_comment',      'uses' => 'ApplicationController@postComment']);
    Route::post('/app/preferences_update',  ['as' => 'update_preferences','uses' => 'ApplicationController@updatePreferences']);
    Route::post('/app/preferences_delete_tags',  ['as' => 'delete_tags_preferences','uses' => 'ApplicationController@deleteTagsPreferences']);
    Route::post('/app/preferences_update_owners',  ['as' => 'update_owners_preferences','uses' => 'ApplicationController@updateOwnersPreferences']);

    // My Apps
    Route::get('/myapps/own',       ['as' => 'my_apps',        'uses' => 'ApplicationController@my_apps']);
    Route::get('/myapps/installed', ['as' => 'installed_apps', 'uses' => 'ApplicationController@installed_apps']);

    Route::get('/top_apps', ['as' => 'top_apps', 'uses' => 'ApplicationController@top_apps']);
    Route::get('/app/comment', ['as' => 'comment_app', 'uses' => 'ApplicationController@comment']);
    Route::post('/app/post_comment', ['as' => 'post_comment', 'uses' => 'ApplicationController@postComment']);
    Route::get('/app/new', ['as' => 'new_app', function() { return view('app.new', []); }]);
    Route::post('app/create', ['as' => 'create_app', 'uses' => 'ApplicationController@createApp'] );
    Route::get('/app/preferences', ['as' => 'preferences', 'uses' => 'ApplicationController@preferences']);

    // Packages
    Route::get('/package',                ['as' => 'package',        'uses' => 'PackageController@index' ]);
    Route::get('/package/edit',           ['as' => 'edit_package',   'uses' => 'PackageController@edit' ]);
    Route::get('/package/delete_confirm', ['as' => 'delete_confirm', 'uses' => 'PackageController@delete_confirm']);

    // Upload packages
    Route::get('/app/upload',             ['as' => 'upload_package', 'uses' => 'PackageController@upload']);
    Route::post('/upload/temporary', ['as' => 'upload_temp', 'uses' => 'PackageController@upload_temp']);
    Route::post('/upload/new', ['as' => 'upload_new', 'uses' => 'PackageController@post_upload']);
    Route::get('/package/install',        ['as' => 'install_package','uses' => 'PackageController@install']);
    Route::get('/package/install_plist', ['as' => 'install_plist',   'uses' => 'PackageController@install_plist']);

    Route::post('/package/delete', ['as' => 'delete_package', 'uses' => 'PackageController@delete']);
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
