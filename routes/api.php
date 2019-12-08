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
Route::group(['middleware' => 'cors'], function () {
    Route::get('recipes','RecipeController@showAll');
    Route::get('categories','CategoryController@show');
    Route::get('ingredients','IngredientController@show');

    Route::get('search','SearchController@index');
    Route::get('recipe/{id}','RecipeController@showRecipe');
    Route::post('add','AddRecipeController@create');

    Route::post('registration','Auth\RegisterController@registration');
    Route::post('login','Auth\LoginController@login');

    Route::group(['middleware' => ['web']], function () {
        Route::get('social-auth/google', 'Auth\LoginController@redirectToProviderLogin');
        Route::get('social-auth/google/callback', 'Auth\LoginController@authenticateSocial');
    });
});