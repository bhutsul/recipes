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

Route::get('recipes','RecipeController@show');
Route::get('categories','CategoryController@show');

Route::get('search','SearchController@index');
Route::post('add','AddRecipeController@create');

Route::post('registration','Auth\RegisterController@registration');
Route::post('login','Auth\LoginController@login');
Route::post('logout', 'Auth\LogoutController')->middleware('auth:api');

Route::group(['middleware' => ['web']], function () {
    Route::get('social-auth/google', 'Auth\LoginController@redirectToProviderLogin');
    Route::get('social-auth/google/callback', 'Auth\LoginController@authenticateSocial');
});
