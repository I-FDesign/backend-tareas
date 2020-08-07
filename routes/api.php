<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('signup', 'AuthController@signUp');
Route::post('login', 'AuthController@login');

Route::group([

    'middleware' => 'api'

], function ($router) {

    //User routes
    Route::post('refresh', 'UserController@refresh');
    Route::post('me', 'UserController@me');

    //Task routes
    Route::get('tasks', 'TasksController@getTasks');
    Route::get('task/{task_id}', 'TasksController@getTask');
    Route::post('new-task', 'TasksController@newTask');

    Route::put('edit-task/{task_id}', 'TasksController@editTask');

    Route::delete('delete-task/{task_id}', 'TasksController@deleteTask');

});

