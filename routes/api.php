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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

    Route::post('login', 'API\AuthController@login');
    Route::post('register', 'API\AuthController@register');
 
    Route::middleware('auth:api')->group(function(){
 
	  Route::post('user_detail', 'API\AuthController@user_detail');	  
	  
	});

    Route::post('get_access_token_based_refresh_token', 'API\AuthController@refresh_based_access');

	Route::middleware('auth:api')->group(function(){
 
	  Route::post('corana_detail', 'API\CoranaController@all_detail');
	  Route::post('add_corana_detail', 'API\CoranaController@add_corana_detail');
	  Route::post('edit_corana_detail', 'API\CoranaController@edit_corana_detail');
	  Route::post('delete_corana_detail', 'API\CoranaController@delete_corana_detail');

	  Route::post('remove_corana_detail/{id}','API\CoranaController@delete_corana_detail_without_query_string');
	  
	});


//   Route::group(['middleware' => 'auth:api'], function(){
// Route::post('user_detail', 'API\AuthController@user_detail');
// });