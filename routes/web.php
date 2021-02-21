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

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


// トップページ
Route::get('/', 'HomeController@index')->name('home.index');

// ログインユーザーを返す
Route::get('/user', fn() => Auth::user())->name('user');

Route::get('/{any?}', fn() => view('pages.records'))->where('any', '.+');
Auth::routes();
