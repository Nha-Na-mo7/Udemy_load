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

// トークンリフレッシュ
Route::get('/reflesh-token', function (Illuminate\Http\Request $request){
  $request->session()->regenerateToken();
  return response()->json();
});
Auth::routes();

// レコードの投稿
Route::post('/records', 'RecordController@create')->name('record.create');

Route::get('/{any?}', 'IndexController@error')->where('any', '.+')->name('home.error');

