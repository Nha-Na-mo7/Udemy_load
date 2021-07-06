<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'organization', 'profile_text',
    ];
  
    // Jsonで表示させる項目
    protected $visible = [
        'id', 'name', 'email', 'records', 'test_user_flg', 'organization', 'profile_text'
    ];
    
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    /**
     * リレーション - recordsテーブル
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function records()
    {
      return $this->hasMany('App\Models\Record');
    }

}
