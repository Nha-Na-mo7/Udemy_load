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
/* TODO Auth::routes()の後に/loginでのルートのビューを指定しないと元のlogin.blade.phpを参照してしまう
後で修正をすること */
// ログイン
Route::get('/login', 'HomeController@index')->name('home.index');


// ====================
// マイページ・ユーザー関連
// ====================
// 指定したユーザー情報の取得
Route::get('/user/info/{username?}', 'UserController@get_user');

// =================
// UdemyAPI関連
// =================
// 指定したワードでUdemyAPIを使用し、レッスン一覧を取得する
Route::get('/udemy/course/get', 'UdemyController@get_course');

// =============
// レコード関連
// =============
// 投稿
Route::post('/records/create', 'RecordController@create')->name('record.create');
// 更新
Route::post('/record/{id}/update', 'RecordController@update')->name('record.update');
// 詳細/編集画面
Route::get('/record/{id}/{owner_flg?}', 'RecordController@show')->name('record.show');
// コメントの投稿
Route::post('/record/{record}/comments', 'RecordController@addComment')->name('record.comment');
// 削除(論理)
Route::post('/record/{id}/delete', 'RecordController@delete')->name('record.delete');
// 一覧取得(ユーザーIDは任意)
Route::get('/records/index/{id?}', 'RecordController@index')->where('id', '[\w]+')->name('record.index');


Route::get('/{any?}', 'IndexController@error')->where('any', '.+')->name('home.error');
