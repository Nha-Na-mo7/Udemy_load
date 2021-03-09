<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Record extends Model
{
    // fill
    protected $fillable = ['user_id', 'title', 'description'];
  
    // プライマリーキーの型をstringに
    protected $keyType = 'string';
    
    // IDの桁数
    const ID_LENGTH = 16;
    
    // コンストラクタでID生成する
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        
        if (!Arr::get($this->attributes, 'id')) {
            $this->setId();
        }
    }
    
    // ランダムなID値を生成
    private function makeRandomId()
    {
        // 0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ
        $characters = array_merge(
            range(0, 9), range('a', 'z'), range('A', 'Z')
        );
        $length = count($characters);
        
        $id = "";
        for ($i = 0; $i < self::ID_LENGTH; $i++) {
          $id .= $characters[random_int(0, $length - 1)];
        }
        
        return $id;
    }
    
    // 作成したランダムなIDをidに代入する
    private function setId()
    {
        $this->attributes['id'] = $this->makeRandomId();
    }
  
  
    /**
     * リレーション - usersテーブル
     */
    public function owner()
    {
      return $this->belongsTo('App\User', 'user_id', 'id', 'users');
    }
    
    /**
     * リレーション - recordsテーブル
     */
    public function courses() {
      return $this->hasMany('App\Models\Record');
    }
}
