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

// All Routes - API Here Not Authenticated
Route::group(['middleware' => ['api', 'checkPassword', 'changeLanguage'], 'namespace' => 'Api'], function (){
    Route::post('get-main-categories', 'CategoriesController@index');
    Route::post('get-category-byId', 'CategoriesController@getCategoryById');
    Route::post('change-category-status', 'CategoriesController@changeCategoryStatus');

    // Admin Login
    Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function(){
        Route::get('login', 'AuthController@login');
    });

});

Route::group(['middleware' => ['api', 'checkPassword',
    'changeLanguage', 'checkAdminToken:admin-api'], 'namespace' => 'Api'], function (){
    Route::get('offers', 'CategoriesController@index');
});
