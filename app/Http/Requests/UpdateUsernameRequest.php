<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUsernameRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
      return true;
    }
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
      
      return [
          'name' => "required|unique:users|string|min:3|max:32|regex:/^[\w]+$/",
      ];
    }
    
    public function messages()
    {
      
      return [
          'name.string' => '半角英数字のみ利用可能です',
          'name.required' => '入力してください',
          'name.unique' => '入力されたユーザーネームは利用できません',
          'name.min' => "3文字以上 32文字以内で入力してください",
          'name.max' => "3文字以上 32文字以内で入力してください",
          'name.regex' => "半角英数字のみ利用可能です",
      ];
    }
}
