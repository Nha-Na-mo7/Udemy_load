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
    
    // ----------------------------------
    // APIへリクエストを飛ばし、コース一覧を取得
    // ----------------------------------
    // リクエストのたびにBasic認証が必要なので、クライアントIDとパスワードを付与する
    $client = new Client();
    $response = $client->request('GET', $api_url, [
        'auth' => [
            config('services.udemy')['client_id'],
            config('services.udemy')['client_password']
        ]
    ]);
    
    // TODO UdemyAPI側に問題があった場合はエラーメッセージを返却させる
    if ($response->getStatusCode() === 500) {
      return response()->json(['error' => 'UdemyAPIにエラーが発生しました。しばらく時間をおいてやり直してください。'], 500);
    }
    
    // 帰ってきた検索データをjsonに
    $body = $response->getBody();
    $data = json_decode($body, true);
    
    // Log::debug($data);
    // Log::debug($data['count']);
    // Log::debug($data['results']);

    // ----------------------
    // 必要な情報をレスポンスする
    // ----------------------
    // 講座数をカウント、0の場合は空のままレスポンスする
    if(!!$data['count']) {
      Log::debug('コース総数: '. $data['count']);
    } else {
      Log::debug('見つかりませんでした、検索ワードを変えてください');
    }
    // レスポンスする記事の配列
    // Log::debug('レスポンスする記事の配列: '. print_r($scraped_entry_list, true));
    
    // 取得したニュースの配列を返却
    return $data;
  }
}
