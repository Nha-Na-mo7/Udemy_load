<?php

namespace App\Http\Controllers;

use App\Models\Record;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecordController extends Controller
{
    public function __construct()
    {
        // 認証必須
        $this->middleware('auth');
    }
    
    // レコードの投稿
    public function create()
    {
        $record = new Record();
        
        try {
          Auth::user()->records()->save($record);
        } catch (\Exception $e) {
          throw $e;
        }
        
        return response($record, 201);
    }
}
