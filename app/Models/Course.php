<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/*
 * Recordテーブルに紐づく、コース1つ1つのテーブル
 */

class Course extends Model
{
    // fill
    protected $fillable = [
        'id',
        'record_id',
        'record_index',
        'course_id',
        'title',
        'instructor',
        'description',
        'url',
        'image_url'
    ];
    
    /**
     * リレーション - recordsテーブル
     */
    public function author() {
      return $this->belongsTo('App\Models\Record', 'record_id', 'id', 'records');
    }
}