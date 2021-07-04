<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/*
 * usersテーブルに紐づくプロフィール文
 */

class Profile extends Model
{
  /**
   * リレーション - usersテーブル
   */
  public function author() {
    return $this->belongsTo('App\Models\User', 'user_id', 'id', 'users');
  }
}
