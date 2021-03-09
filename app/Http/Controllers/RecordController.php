<?php

namespace App\Http\Controllers;

use App\Models\Record;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RecordController extends Controller
{
    public function __construct()
    {
        // 認証必須
        $this->middleware('auth');
    }
    
    // レコードの投稿
    public function create(Request $request)
    {
        Log::debug('==============');
        Log::debug(' レコードの投稿');
        Log::debug('==============');
        
        Log::debug('$request');
        Log::debug(print_r($request->input('selectedCourses'), true));
        Log::debug(print_r($request->input('recordForm'), true));
        
        // $record = new Record();
        // $keep_id = $record->id;
        
        // recordsテーブルにtitleとdescriptionを格納
        // coursesテーブルに選択されたコース、コースの説明、レコードの何番目かなどの情報を格納する
      
        // return response($record, 201);
        // TODO テスト用
        return response()->json([], 201);
    }
}
