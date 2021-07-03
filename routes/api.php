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

// All Routes - API's Here Are Authenticated
Route::group(['middleware' => ['api', 'checkPassword', 'changeLanguage'], 'namespace' => 'Api'], function (){
    Route::post('get-main-categories', 'CategoriesController@index');
    Route::post('get-category-byId', 'CategoriesController@getCategoryById');
    Route::post('change-category-status', 'CategoriesController@changeCategoryStatus');

    // Admin API's
    Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function(){
        Route::post('login', 'AuthController@login');
        Route::post('logout', 'AuthController@logout')->middleware('assignGuard:admin-api');
    });

    // User API's
    Route::group(['prefix' => 'user', 'namespace' => 'User'], function(){
        Route::post('login', 'AuthController@login');
        Route::post('logout', 'AuthController@logout')->middleware('assignGuard:user-api');
    });

    Route::group(['prefix' => 'user', 'middleware' => 'assignGuard:user-api'], function(){
        Route::post('profile', function(){
            // Only Authenticated User Can Show This
            return 'Only Authenticated User Can Reach Me.';
        });
        Route::post('get-user-data', function(){
            // Return Authenticated User Data
            return \Auth::user();
        });
    });
});

Route::group(['middleware' => ['api', 'checkPassword',
    'changeLanguage', 'checkAdminToken:admin-api'], 'namespace' => 'Api'], function (){
    Route::get('offers', 'CategoriesController@index');
});
