<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/*
 * Recordテーブルに紐づく、コース1つ1つのテーブル
 */

class Course extends Model
{
    //
  
    /**
     * リレーション - recordsテーブル
     */
    public function author() {
      return $this->belongsTo('App\Models\Record', 'record_id', 'id', 'records');
    }
}