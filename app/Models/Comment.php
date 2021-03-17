<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    // JSON
    protected $visible = [
        'author', 'content'
    ];
  
    /**
     * リレーション - usersテーブル
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author()
    {
      return $this->belongsTo('App\Models\User', 'user_id', 'id', 'users');
    }
}
