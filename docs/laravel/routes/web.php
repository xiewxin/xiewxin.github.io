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
    // 輔助函數
    Route::group(['prefix' => '/auxiliary', 'namespace' => 'Auxiliary', 'as' => 'home.auxiliary.'], function () {
        Route::any('/arr', 'ArrController@index');
        Route::any('/path', 'PathController@index');
        Route::any('/string', 'StringController@index');
    });
    
    // 集合
    Route::group(['prefix' => '/collect', 'namespace' => 'Collect', 'as' => 'home.collect.'], function () {
        Route::any('/', 'CollectController@index');
    });
    
    // 練習
    Route::group(['prefix' => '/exercise', 'namespace' => 'Exercise', 'as' => 'home.exercise.'], function () {
        Route::any('/collect1', 'CollectController@collect1');
        Route::any('/collect2', 'CollectController@collect2');
        Route::any('/collect3', 'CollectController@collect3');
        Route::any('/collect4', 'CollectController@collect4');
        Route::any('/collect5', 'CollectController@collect5');
    });
});
