<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePasswordRequest;
use App\Http\Requests\UpdateMailRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateUsernameRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function __construct(){
      $this->middleware('auth')->except(['index', 'get_user',]);
    }
    // ==========================
    // ビューの返却(settings)
    // ==========================
    public function index() {
      return view('pages.settings');
    }
    
    // ==============
    // 現在認証中か
    // ==============
    public function auth_check() {
      Log::debug('認証状態のチェック auth_check::'.Auth::check());
      if(Auth::check()){
        Log::debug('200 認証中です');
        return response()->json([], 200);
      }
  
      Log::debug('401 認証切れです');
      return response()->json([], 401);
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
  
    // =========================
    // ユーザーネームの更新
    // =========================
    public function update_name(UpdateUsernameRequest $request) {
      Log::debug('UserController.update_name ユーザーネームの更新');
      try {
        $user = Auth::user();
        
        $new_name = $request->name;
        Log::debug('変更後のユーザーネーム: '.$new_name);
        
        $user->name = $new_name;
        $user->save();
        
        Log::debug('ユーザーネームの変更完了しました。');
        session()->flash('session_success', 'ユーザーネームを更新しました');
        return response()->json([], 200);
      }catch(\Exception $e) {
        Log::debug('エラーが発生しました。'. $e->getMessage());
        session()->flash('session_error', 'エラーが発生しました。しばらくしてからやり直してください。');
        return response()->json([], 500);
      }
    }
    
    // =========================
    // メールアドレスの更新
    // =========================
    // TODO 暫定で入力後に即更新とするが、変更後に確認メールを送信する処理を後ほど追加する。
    public function update_email(UpdateMailRequest $request) {
      Log::debug('UserController.update_mail メールアドレスの更新');
      try {
        $user = Auth::user();
        
        $new_email = $request->email;
        Log::debug('変更後のemail :'.$new_email);
  
        $user->email = $new_email;
        $user->save();
        
        Log::debug('メールアドレスが変更されました。');
        session()->flash('session_success', 'メールアドレスを更新しました！');
        return response()->json([], 200);
      }catch(\Exception $e) {
        Log::debug('エラーが発生しました。'. $e->getMessage());
        session()->flash('session_error', 'エラーが発生しました');
        return response()->json([], 500);
      }
    }
    
    // =========================
    // パスワードを新しく設定する
    // =========================
    // Twitterで新規登録した場合、パスワードは空の状態でユーザーが作成される。
    // そのため更新処理とは別に新規パスワード作成を用意する。
    public function create_password(CreatePasswordRequest $request) {
      Log::debug('UserController.password_create パスワード新規作成');
      try {
        $user = Auth::user();
        
        $user->password = Hash::make($request->password);
        $user->save();
        
        Log::debug('パスワードを新規登録しました。');
        session()->flash('session_success', 'パスワードが登録されました');
        return response()->json([], 200);
        
      }catch (\Exception $e){
        Log::debug('エラーが発生しました。'. $e->getMessage());
        session()->flash('session_error', 'エラーが発生しました');
        return response()->json([], 500);
      }
    }
    
    // =========================
    // パスワードを更新する
    // =========================
    // こちらは純粋なパスワードの更新処理メソッド。
    public function update_password(UpdatePasswordRequest $request) {
      Log::debug('UserController.password_update パスワードの更新');
      
      try {
        // ログインユーザーの情報を取得
        $user = Auth::user();
        
        // 更新の場合は旧パスワードと一致するかを確認する工程が入る
        // Hash::makeでは毎回違うハッシュ値になるので比較できない
        // Hash::checkを使ってDBのパスワードと比較する
        if(!Hash::check($request->get('old_password'), $user->password)) {
          Log::debug('old_passwordは一致しませんでした。422をreturnします。');
          return response()->json(
              ['errors' =>
                  ['old_password' =>
                      ['現在のパスワードが一致しません']
                  ]
              ], 422);
        }
        
        // TODO テストユーザーの場合は更新処理は行われない
        // if ($user->test_user_flg) {
        //   return response()->json(
        //       ['success' => '【テストユーザーはパスワード変更できません】'], 200);
        // }
        
        Log::debug('old_passwordが認証ユーザーのテーブルのパスワードと一致しました。更新処理を開始します。');
        $user->password = Hash::make($request->password);
        $user->save();
        
        Log::debug('パスワードが更新されました');
        
        return response()->json(['success' => 'パスワードを更新しました！'], 200);
        
      } catch (\Exception $e){
        Log::debug('エラーが発生しました。'. $e->getMessage());
        return response()->json(['errors' => 'エラーが発生しました。'], 500);
      }
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

        session()->flash('session_msg', '退会処理が完了しました。');
        return response([], 200);
        
      } catch (\Exception $e) {
        Log::debug('退会処理の過程でエラーです。'. $e->getMessage());
        // ログアウト
        Auth::logout();
        // セッションを一度消してから再発行
        session()->invalidate();
        // csrfトークンを再生成
        session()->regenerateToken();

        session()->flash('session_msg', '退会処理中にエラーが発生しました');
        return response([], 500);
      }
    }
}