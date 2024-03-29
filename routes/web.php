<?php

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

Route::get('/', function () {
    return view('welcome');
});

/*Route::post('/api/register', 'UserController@register');
Route::post('/api/login', 'UserController@login');*/

Route::post('/api/register', ['middleware' => 'cabeceras',
                              'uses' => 'UserController@register']);

Route::post('/api/login', ['middleware' => 'cabeceras',
                           'uses' => 'UserController@login']);

Route::group(['middleware' => 'cabeceras'], function() {
    Route::resource('/api/guitars', 'GuitarController');
});

Route::group(['middleware' => 'cabeceras'], function() {
    Route::resource('/api/brands', 'BrandController');
});