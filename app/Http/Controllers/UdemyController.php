<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UdemyController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }
  
  // ===========================
  // ビューを返却する
  // ===========================
  // public function index(){
  //   return view('pages.news');
  // }
  
  /* ============================
   * Udemy 講座検索
   * ============================
   * $keyword: 検索のキーワード
   */
  public function get_lesson(){
    
    Log::debug('============================================');
    Log::debug('Udemy_C/get_lesson 講座情報を取得します');
    Log::debug('============================================');
    // ------------------
    // APIアクセス前準備
    // ------------------
    // GETパラメータの値を元に、講座情報を取得する
    $keywords = filter_input(INPUT_GET, 'keywords') ? filter_input(INPUT_GET, 'keywords') : 'Ruby on Rails';
    
    Log::debug('検索ワード: '.$keywords);
    
    // 最大実行時間・90秒。
    set_time_limit(90);
    
    // APIへのリクエストURLを作成
    // $api_url = 111111111;
    // Log::debug('APIリクエストURL :'.$api_url);
    
    // --------------------
    // APIへリクエストを飛ばす
    // --------------------
    
    // ----------------------
    // 必要な情報をレスポンスする
    // ----------------------
    // 講座情報一覧を取得
    $data = [1, 2, 3, 4, 5];
    
    // 講座数をカウント、0の場合は空のままレスポンスする
    if(count($data)) {
      Log::debug('見つけた記事数: '. count($data));
    } else {
      Log::debug('記事は0件でした。');
      return $data;
    }
    
    // "title"などの必要情報を取り出して、配列に格納する
    
    // 記事を並べ替える。
    $lesson_list = [7, 5, 3];
    
    // レスポンスする記事の配列
    // Log::debug('レスポンスする記事の配列: '. print_r($scraped_entry_list, true));
    
    // 取得したニュースの配列を返却
    return $lesson_list;
  }
}
