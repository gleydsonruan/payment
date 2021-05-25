<?php

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

Route::group(
    ['prefix' => 'transactions'], function () {
        Route::get('/', 'App\Http\Controllers\TransactionController@index');
        Route::get('/{id}', 'App\Http\Controllers\TransactionController@show');
        Route::post('/', 'App\Http\Controllers\TransactionController@store');
    }
);
