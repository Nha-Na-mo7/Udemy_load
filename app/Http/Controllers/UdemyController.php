<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;

class UdemyController extends Controller
{
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
  public function get_course(){
    
    Log::debug('================================');
    Log::debug('Udemy_C:get_course 講座情報を取得');
    Log::debug('================================');
    // ------------------
    // APIアクセス前準備
    // ------------------
    // GETパラメータの値を元に、講座情報を取得する
    $keywords = filter_input(INPUT_GET, 'keywords') ? filter_input(INPUT_GET, 'keywords') : '';
    
    Log::debug('検索ワード: '.$keywords);

    // APIへのリクエストURLを作成
    // キーワード検索時のベースとなるURL
    $API_BASE_URL = 'https://www.udemy.com/api-2.0/courses/?language=ja&search=';
    // 検索キーワードの文字コードを変更
    $query = urlencode(mb_convert_encoding($keywords, "UTF-8", "auto"));
  
    // APIへのリクエストURLを作成
    $api_url = $API_BASE_URL . $query;
    
    // --------------------
    // APIへリクエストを飛ばす
    // --------------------
    // リクエストのたびにBasic認証が必要なので、クライアントIDとパスワードを付与する
    $client = new Client();
    $response = $client->request('GET', $api_url, [
        'auth' => [
            config('services.udemy')['client_id'],
            config('services.udemy')['client_password']
        ]
    ]);
    $body = $response->getBody();
    $data = json_decode($body, true);
    
    Log::debug($data);

    // ----------------------
    // 必要な情報をレスポンスする
    // ----------------------
    // 講座情報一覧を取得
    
    // 講座数をカウント、0の場合は空のままレスポンスする
    // if(count($data)) {
    //   Log::debug('見つけた記事数: '. count($data));
    // } else {
    //   Log::debug('記事は0件でした。');
    //   return $data;
    // }
    
    // "title"などの必要情報を取り出して、配列に格納する
    
    // レスポンスする記事の配列
    // Log::debug('レスポンスする記事の配列: '. print_r($scraped_entry_list, true));
    
    // 取得したニュースの配列を返却
    return 'OK';
  }
}
