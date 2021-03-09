<?php

namespace App\Http\Controllers;

use App\Models\Course;
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
        // 選択されたコースの個数
        Log::debug(count($request->selectedCourses));
        
        $record = new Record();
        $keep_id = $record->id;
        
        // recordsテーブルにtitleとdescriptionを格納
        Auth::user()->records()->save($record->fill($request->recordForm));
        Log::debug($record->id);
        
        // coursesテーブルに選択されたコース、コースの説明、レコードの何番目かなどの情報を格納する
        for ($i = 0, $iMax = count($request->selectedCourses); $i < $iMax; $i++) {
  
          $course = new Course();
          
          $courseData = $request->get('selectedCourses')[$i];
          $courseObj = $courseData['courseObject'];
          
          Log::debug('index : ' . ($i + 1));
          
          // contentsテーブルへ本文を格納
          $course->record_id = $keep_id;
          $course->record_index = $i;
          $course->course_id = $courseObj['id'];
          $course->title = $courseObj['title'];
          $course->instructor = $courseObj['visible_instructors'][0]['title'];
          $course->url = $courseObj['url'];
          $course->image_url = $courseObj['image_240x135'];
          $course->description = $courseData['description'];
          $course->save();
        }
        
        return response($record, 201);
    }
}
