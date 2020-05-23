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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/colors', 'ColorController@index')->name('colors');
Route::get('/color/add', 'ColorController@add')->name('color_add');
Route::get('/color/edit/{id}', 'ColorController@add')->where('id', '[0-9]+')->name('color_edit');
Route::post('/color/save', 'ColorController@save')->name('color_save');
Route::get('/color/del/{id}', 'ColorController@del')->where('id', '[0-9]+')->name('color_del');

