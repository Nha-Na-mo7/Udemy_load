<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Record extends Model
{
    // fill
    protected $fillable = ['user_id', 'title', 'description'];
    
    // Jsonで表示させる項目
    protected $visible = [
        'id', 'user_id', 'title', 'description', 'owner', 'courses', 'comments'
    ];
  
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
      return $this->belongsTo('App\Models\User', 'user_id', 'id', 'users');
    }
    
    /**
     * リレーション - recordsテーブル
     */
    public function courses()
    {
      return $this->hasMany('App\Models\Course');
    }
    
    /**
     * リレーション - recordsテーブル
     * IDの降順
     */
    public function comments()
    {
      return $this->hasMany('App\Models\Comment')->orderBy('id', 'desc');
    }
}
