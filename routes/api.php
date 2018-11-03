<?php

use Illuminate\Http\Request;

Route::post('auth/login', 'API\AuthController@authenticate');
Route::post('auth/register', 'API\AuthController@register');

Route::group(['namespace' => 'API'], function(){
    Route::get('auth/me', 'AuthController@me');

    // Doodle
    Route::get('doodles', 'DoodlesController@index');
    Route::get('doodle/{id}', 'DoodlesController@show');
    Route::get('doodles/user/{user_id}', 'DoodlesController@allDoodlesOfUser');
    Route::post('doodle', 'DoodlesController@store');
    Route::put('doodle/{id}', 'DoodlesController@update');
    Route::post('doodle/{id}/image', 'DoodlesController@updateImage');
    Route::delete('doodle/{id}', 'DoodlesController@destroy');
    Route::post('doodle/{doodle_id}', 'DoodlesController@like');
    Route::post('doodle/{doodle_id}', 'DoodlesController@dislike');

    // comments
    Route::post('comments', 'CommentsController@store');
    Route::put('comment/{id}', 'CommentsController@update');
    Route::delete('comment/{id}', 'CommentsController@destroy');

    // comments
    Route::post('reply', 'RepliesController@store');
    Route::put('reply/{id}', 'RepliesController@update');
    Route::delete('reply/{id}', 'RepliesController@destroy');

});
