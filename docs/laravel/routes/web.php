<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['prefix' => 'home', 'namespace' => 'Home'], function () {
    Route::group(['prefix' => '/auxiliary', 'namespace' => 'Auxiliary', 'as' => 'home.auxiliary.'], function () {
        Route::any('/arr', 'ArrController@index');
        Route::any('/path', 'PathController@index');
        Route::any('/string', 'StringController@index');
    });
});
