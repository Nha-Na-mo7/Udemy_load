<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function __construct(){
      $this->middleware('auth')->except(['get_user']);
    }
    // =========================================================
    // 指定したユーザーの情報を返却する。指定がない場合は自分の情報を返却する
    // =========================================================
    public function get_user($username = null) {
      Log::debug('UserController.get_user ユーザー情報を取得する');
      if ($username === null) {
        Log::debug('$usernameがnullなので自分の情報を返却します');
        return Auth::user();
      }
      Log::debug('$username : '.$username . 'で検索し、存在しなければ404を返します');
      return User::where('name', $username)->firstOrFail();
    }
    
    // ======================
    // 退会
    // ======================
    public function withdraw(){
      Log::debug('UserController.withdraw 退会処理');
      try {
        // 認証済みユーザーを取得
        $user = Auth::user();
        // 先にログアウトしてから後続の処理を行う
        Auth::logout();
        // user情報を削除する
        $user->delete();
        // セッションを一度消してから再発行
        session()->invalidate();
        // csrfトークンを再生成
        session()->regenerateToken();
        
        // \Session::flash('system_message', '退会しました。ご利用いただきありがとうございました。');
        return response(200);
        
      } catch (\Exception $e) {
        Log::debug('退会処理の過程でエラーです。'. $e->getMessage());
        // ログアウト
        Auth::logout();
        // セッションを一度消してから再発行
        session()->invalidate();
        // csrfトークンを再生成
        session()->regenerateToken();
        // \Session::flash('system_message', '退会処理中にエラーが発生しました。');
        return response(500);
      }
    }
}