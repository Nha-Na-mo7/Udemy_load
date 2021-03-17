<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreComment;
use App\Models\Comment;
use App\Models\Course;
use App\Models\Record;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RecordController extends Controller
{
    public function __construct()
    {
        // 基本的に認証必須 / expectは認証不要
        $this->middleware('auth')->except(['show', 'index']);
    }
    
    // レコードの投稿
    // TODO 未実装項目: IDが被った時に再抽選する機能、
    public function create(Request $request)
    {
        Log::debug('==============');
        Log::debug(' レコードの投稿');
        Log::debug('==============');
        
        // Log::debug(print_r($request->input('selectedCourses'), true));
        // Log::debug(print_r($request->input('recordForm'), true));
        $record = new Record();
        $keep_id = $record->id;
        
        // recordsテーブルにtitleとdescriptionを格納
        Auth::user()->records()->save($record->fill($request->recordForm));
        // idが0になるのでkeep_idを格納
        $record->id = $keep_id;
        
        // coursesテーブルに選択されたコース、コースの説明、レコードの何番目かなどの情報を格納する
        for ($i = 0, $iMax = count($request->selectedCourses); $i < $iMax; $i++) {
            $course = new Course();
            
            $courseData = $request->get('selectedCourses')[$i];
            $courseObj = $courseData['courseObject'];
            
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
    
    // レコード詳細の取得
    public function show(string $id) {
        Log::debug('==================================');
        Log::debug('レコード詳細取得/ID:'.$id);
        Log::debug('==================================');
        // IDに合致するレコード情報を取得
        $record = Record::where('id', $id)->with(['owner', 'courses', 'comments.author'])->first();
        // descriptionの改行タグを<br>に置き換え
        $record->description = str_replace("\r\n", '<br>', $record->description);
        
        // TODO course内のindex項目による並び替えを行ってからreturnしてください
        // レコードを返すが、存在しない場合は404を返す
        return $record ?? abort(404);
    }
    
    // レコード一覧の取得
    public function index($user_id = null) {
        Log::debug('==============');
        Log::debug('レコード一覧取得');
        Log::debug('==============');
        // ユーザーIDがない場合、全てのレコードを取得する
        if ($user_id === null) {
            Log::debug('USER_ID:'.$user_id);
            $records = Record::with(['owner'])
                ->orderBy(Course::CREATED_AT, 'desc')
                ->paginate();
      
            return $records;
        }
        // ユーザーIDがある場合、そのユーザーが投稿したレコードに絞って取得する
      
        $records = Record::where('user_id', $user_id)
            ->with(['owner'])
            ->orderBy(Record::CREATED_AT, 'desc')
            ->paginate();
        
        return $records;
    }
    
    // レコードの削除
    public function delete(string $id) {
        Log::debug('===============================');
        Log::debug('レコード削除/ID:'.$id);
        Log::debug('===============================');
  
        // 現在認証中のユーザーの投稿済みレコードの中に指定したIDのレコードがあれば取得する
        $record = Auth::user()->records()->find($id);
        if ($record) {
            // delete_flgをtrueにする
            $record->delete_flg->save(true);
            Log::debug($id.'のdelete_flgをtrueにし、論理削除されました。');
            return response([], 200);
        }
        // TODO ない場合404を返す?500にする?
        Log::debug('レコードはありませんでした');
        return abort(404);
    }
    
    // コメントの投稿
    public function addComment(Record $record, StoreComment $request) {
        Log::debug('===========');
        Log::debug('コメント投稿');
        Log::debug('===========');
        $comment = new Comment();
        $comment->content = $request->get('content');
        $comment->user_id = Auth::user()->id;
        $record->comments()->save($comment);
        
        // authorリレーションロードようにコメントを取得し直す
        $new_comment = Comment::where('id', $comment->id)->with('author')->first();
        
        return response($new_comment, 201);
    }
}
