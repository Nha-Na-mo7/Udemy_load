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
// 認証系ルート
Auth::routes();
/* TODO Auth::routes()の後に/loginでのルートのビューを指定しないと元のlogin.blade.phpを参照してしまう
後で修正をすること */
// ログイン
Route::get('/login', 'HomeController@index')->name('home.index');

// ===============
// マイページ関連
// ===============
// 指定したユーザー情報の取得
Route::get('/user/info/{username?}', 'UserController@get_user');
// マイページのビュー
Route::get('/mypage/{any?}', 'MypageController@index')->name('mypage.index')->where('any', '.+');

// =============
// レコード取得
// =============
// 詳細/編集画面
Route::get('/record/{id}/{owner_flg?}', 'RecordController@show')->name('record.show');
// 一覧取得(ユーザーIDは任意)
Route::get('/records/index/{id?}', 'RecordController@get_list')->where('id', '[\w]+');

// 投稿画面のビュー
Route::get('/records/new', 'RecordController@index_create')->name('record.index');
// レコード詳細画面のビュー
Route::get('/records/{any?}', 'RecordController@index')->name('record.index')->where('any', '.+');


// 以下は認証必須
Route::group(['middleware' => 'auth'], function() {
  // ==============
  // レコード編集関連
  // ==============
  // 投稿
  Route::post('/records/create', 'RecordController@create')->name('record.create');
  // 更新
  Route::post('/record/{id}/update', 'RecordController@update')->name('record.update');
  // コメントの投稿
  Route::post('/record/{record}/comments', 'RecordController@addComment')->name('record.comment');
  // 削除(論理)
  Route::post('/record/{id}/delete', 'RecordController@delete')->name('record.delete');

  // ===============
  // UdemyAPI
  // ===============
  // 指定したワードでUdemyAPIを使用し、レッスン一覧を取得する
  Route::get('/udemy/course/get', 'UdemyController@get_course');

  // ===============
  // アカウント設定関連
  // ===============
  // ユーザーネームを更新する
  Route::post('/user/update/name', 'UserController@update_name');
  // メールアドレスの更新処理
  Route::post('/user/update/email', 'UserController@update_email');
  // TODO メールアドレスのリセットを確定
  Route::get('/user/update/email/{token}', 'UserController@reset_email');
  // パスワードの新規登録
  Route::post('/user/create/password', 'UserController@create_password');
  // パスワードの更新
  Route::post('/user/update/password', 'UserController@update_password');
  // 退会処理
  Route::post('/withdraw', 'UserController@withdraw')->name('user.withdraw');

  // アカウント設定のビュー
  Route::get('/settings/{any?}', 'UserController@index')->name('settings.index')->where('any', '.+');
});
// ==========================================================
// 上記以外の全てのページに対してはNotFoundとしてerror.blade.phpを返す
// ==========================================================
Route::get('/{any?}', 'IndexController@error')->where('any', '.+')->name('home.error');
