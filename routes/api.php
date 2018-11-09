<?php

use Illuminate\Http\Request;

Route::post('auth/login', 'API\AuthController@authenticate');
Route::post('auth/register', 'API\AuthController@register');

// doodle
Route::get('doodles', 'API\DoodlesController@index');
Route::get('doodle/{id}', 'API\DoodlesController@show');

// comment
Route::get('doodle/{id}/comments', 'API\CommentsController@index');

Route::group(['middleware' => 'jwt.auth', 'namespace' => 'API'], function(){

    // Auth
    Route::get('auth/me', 'AuthController@me');
    Route::put('auth/update-profile', 'AuthController@update');
    
    // Doodle
    Route::get('doodle-with-frames/{id}', 'DoodlesController@showWithFrames');
    Route::get('doodles/user/{user_id}', 'DoodlesController@allDoodlesOfUser');
    Route::post('doodle', 'DoodlesController@store');
    Route::put('doodle/{id}', 'DoodlesController@update');
    Route::post('doodle/{id}/image', 'DoodlesController@updateImage');
    Route::delete('doodle/{id}', 'DoodlesController@destroy');
    Route::post('doodle/{doodle_id}/like', 'DoodlesController@like');
    Route::post('doodle/{doodle_id}/dislike', 'DoodlesController@dislike');
    Route::put('animation-detail/{id}', 'DoodlesController@updateAnimationDetails');

    // frames
    Route::post('frame', 'FramesController@store');
    Route::put('frame/{id}', 'FramesController@update');
    Route::delete('frame/{id}', 'FramesController@destroy');

    // comments
    Route::post('comment', 'CommentsController@store');
    Route::put('comment/{id}', 'CommentsController@update');
    Route::delete('comment/{id}', 'CommentsController@destroy');

    // comments
    Route::post('reply', 'RepliesController@store');
    Route::put('reply/{id}', 'RepliesController@update');
    Route::delete('reply/{id}', 'RepliesController@destroy');

});
