<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $message = [
            'name.required' => '入力してください。',
            'name.unique' => '入力されたユーザーネームは使用できません。',
            'name.string' => '半角英数字で入力してください。',
            'name.min' => '3文字以上で入力してください。',
            'name.max' => '32文字以内で入力してください。',
            'name.regex' => '半角英数字で入力してください。',
          
            'email.required' => '入力してください。',
            'email.unique' => '入力されたメールアドレスは使用できません。他のメールアドレスを入力してください。',
            'email.string' => 'メールアドレスの形式で入力してください。',
            'email.email' => 'メールアドレスの形式で入力してください。',
            'email.max' => '100文字以内で入力してください。',
          
            'password.required' => '入力してください。',
            'password.string' => '半角英数字で入力してください。',
            'password.min' => '8文字以上で入力してください。',
            'password.max' => '50文字以内で入力してください。',
            'password.confirmed' => 'パスワードと再入力が一致しません。',
            'password.regex' => '半角英数字で入力してください。',
        ];
        
        return Validator::make($data, [
            // ユーザーネーム: 半角英数字とアンダースコア
            'name' => ['required', 'string', 'min:3', 'max:32', 'regex:/^[\w]+$/', 'unique:users'],
            'email' => ['required', 'string', 'email:strict,dns', 'max:100', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'max:50', 'confirmed',  'regex:/^[a-zA-Z0-9]+$/'],
        ], $message);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
  
    // registeredメソッドを上書き
    protected function registered(Request $request, $user)
    {
        session()->flash('session_msg', '登録ありがとうございます');
        return $user;
    }
}
