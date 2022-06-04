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

Route::get('/laravel', function () {
    return view('welcome');
});
Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');

Route::get('/', 'DashboardController@home')->name('home');

Route::get('/colors', 'ColorController@index')->name('colors');
Route::get('/color/add', 'ColorController@add')->name('color_add');
Route::get('/color/edit/{id}', 'ColorController@add')->where('id', '[0-9]+')->name('color_edit');
Route::post('/color/save', 'ColorController@save')->name('color_save');
Route::get('/color/del/{id}', 'ColorController@del')->where('id', '[0-9]+')->name('color_del');

Route::get('/icons', 'IconController@index')->name('icons');
Route::get('/icon/add', 'IconController@add')->name('icon_add');
Route::post('/icon/save', 'IconController@save')->name('icon_save');
Route::get('/icon/del/{id}', 'IconController@del')->where('id', '[0-9]+')->name('icon_del');

Route::get('/currencies', 'CurrencyController@index')->name('currencies');
Route::get('/currency/add', 'CurrencyController@add')->name('currency_add');
Route::get('/currency/edit/{id}', 'CurrencyController@add')->where('id', '[0-9]+')->name('currency_edit');
Route::post('/currency/save', 'CurrencyController@save')->name('currency_save');
Route::get('/currency/del/{id}', 'CurrencyController@del')->where('id', '[0-9]+')->name('currency_del');

Route::get('/paymentmodes', 'PaymentModesController@index')->name('paymentmodes');
Route::get('/paymentmode/add', 'PaymentModesController@add')->name('paymentmode_add');
Route::get('/paymentmode/edit/{id}', 'PaymentModesController@add')->where('id', '[0-9]+')->name('paymentmode_edit');
Route::post('/paymentmode/save', 'PaymentModesController@save')->name('paymentmode_save');
Route::get('/paymentmode/del/{id}', 'PaymentModesController@del')->where('id', '[0-9]+')->name('paymentmode_del');

Route::get('/accounts', 'AccountController@index')->name('accounts');
Route::get('/accounts/balance_update', 'AccountController@balanceUpdate')->name('balance_update');
Route::get('/account/add', 'AccountController@add')->name('account_add');
Route::get('/account/edit/{id}', 'AccountController@add')->where('id', '[0-9]+')->name('account_edit');
Route::post('/account/save/', 'AccountController@save')->name('account_save');
Route::delete('/account/del', 'AccountController@del')->name('account_del');

Route::get('/categories', 'CategoryController@index')->name('categories');
Route::get('/category/add', 'CategoryController@add')->name('category_add');
Route::get('/category/edit/{id}', 'CategoryController@add')->where('id', '[0-9]+')->name('category_edit');
Route::post('/category/save/', 'CategoryController@save')->name('category_save');
Route::delete('/category/del', 'CategoryController@del')->name('category_del');

Route::any('/transactions/{aid?}', 'TransactionController@index')->where('aid', '[0-9]+')->name('transaction_view');
Route::get('/transaction/add/', 'TransactionController@add')->name('transaction_add');
Route::get('/transaction/edit/{id}', 'TransactionController@add')->where('id', '[0-9]+')->name('transaction_edit');
Route::post('/transaction/save/', 'TransactionController@save')->name('transaction_save');
Route::delete('/transaction/del', 'TransactionController@del')->name('transaction_del');
Route::get('/transactions/export', 'TransactionController@export')->name('transactions_export');
Route::post('/transactions/delall', 'TransactionController@delall')->name('del_all');

Route::get('/profile', 'ProfileController@edit')->name('profile');
Route::post('/profile/save/', 'ProfileController@save')->name('profile_save');

Route::get('/import', 'ImportController@index')->name('import');
Route::post('/import/save/', 'ImportController@save')->name('import_save');

Route::get('/filters', 'FilterController@index')->name('filters');
Route::get('/filter/add', 'FilterController@add')->name('filter_add');
Route::get('/filter/edit/{id}', 'FilterController@add')->where('id', '[0-9]+')->name('filter_edit');
Route::post('/filter/save/', 'FilterController@save')->name('filter_save');
Route::delete('/filter/del', 'FilterController@del')->name('filter_del');
