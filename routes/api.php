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

Route::prefix('v1')->name('api.')->group(function () {

    // Category
    Route::resource('category', 'Api\CategoryController');
    Route::delete('category', 'Api\CategoryController@destroyMultiple')->name('category.destroyMultiple');

    // Brand
    Route::resource('brand','Api\BrandController');
    Route::delete('brand','Api\BrandController@destroyMultiple')->name('brand.destroyMultiple');

    // Item
    Route::resource('item','Api\ItemController');
    Route::delete('item','Api\ItemController@destroyMultiple')->name('item.destroyMultiple');

});
