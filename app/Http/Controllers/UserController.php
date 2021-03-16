<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function __construct(){
      $this->middleware('auth');
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
}