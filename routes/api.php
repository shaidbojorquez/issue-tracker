<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', 'AuthController@login');
    Route::post('signup', 'AuthController@signup');

    Route::group(['middleware' => 'auth:api'], function() {
        Route::get('logout', 'AuthController@logout');
        Route::get('user', 'AuthController@user')->name('user.profile');
    });
});

Route::group(['middleware' => 'auth:api'], function() {
    Route::get('user/{user}', 'UserController@show')->name('user.show');
    Route::post('issue/{issue}/attach', 'IssueController@attach')->name('issue.attach');
    Route::apiResource('issue', 'IssueController');
    Route::apiResource('project', 'ProjectController');
    Route::apiResource('doc', 'DocController');
    Route::get('/files', 'FilesController@getFile')->name('src');
});