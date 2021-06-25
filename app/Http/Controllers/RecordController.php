<?php

namespace App\Http\Controllers;

use App\Http\Requests\RecordRequest;
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
        $this->middleware('auth')->except(['show', 'index', 'get_list']);
    }
    
    // ============
    // ビューを返却
    // ============
    // 詳細画面
    public function index()
    {
      return view('pages.records');
    }
    // 投稿画面
    public function index_create()
    {
      return view('pages.record_create');
    }
  
    // ==============
    // ロードマップの投稿
    // TODO 未実装項目: IDが被った時に再抽選する機能、
    // ============
    public function create(RecordRequest $request)
    {
        Log::debug('==============');
        Log::debug(' ロードマップ投稿');
        Log::debug('==============');
        
        Log::debug(print_r($request->input('selectedCourses'), true));
        Log::debug(print_r($request->input('recordForm'), true));
        $record = new Record();
        $keep_id = $record->id;
        
        // recordsテーブルにtitleとdescriptionを格納
        Auth::user()->records()->save($record->fill($request->recordForm));
        // idが0になるのでkeep_idを格納
        $record->id = $keep_id;
        
        // coursesテーブルに選択されたコース、コースの説明、ロードマップの何番目かなどの情報を格納する
        for ($i = 0, $iMax = count($request->selectedCourses); $i < $iMax; $i++) {
            $courseData = $request->get('selectedCourses')[$i];
            $courseObj = $courseData['courseObject'];
            
            // contentsテーブルへ本文を格納
            $course = Course::create([
                'record_id' => $keep_id,
                'record_index' => $i,
                'course_id' => $courseObj['id'],
                'title' => $courseObj['title'],
                'instructor' => $courseObj['visible_instructors'][0]['title'],
                'url' => $courseObj['url'],
                'image_url' => $courseObj['image_240x135'],
                'description' => $courseData['description']
            ]);
        }
        
        session()->flash('session_success', '投稿しました');
        return response($record, 201);
    }
    
    // ============
    // ロードマップの更新
    // ============
    public function update(RecordRequest $request, string $id)
    {
        Log::debug('==========================');
        Log::debug(' ロードマップ更新 ID:' . $id);
        Log::debug('==========================');
        
        // TODO 認証中ユーザーか二重チェックする？
        // IDに合致するロードマップを取得
        $record = Record::where('id', $id)
            ->with(['courses'])
            ->where('delete_flg', false)
            ->first();
        
        if ($record === null) {
          Log::debug('ロードマップが見当たらない、あるいは既に削除済みです');
          session()->flash('session_error', 'ロードマップが見つかりませんでした');
          return abort(404);
        }
        // recordsテーブルのtitleとdescriptionを更新
        Auth::user()->records()->save($record->fill($request->recordForm));
        // Log::debug('print_r($record, true)');
        // Log::debug(print_r($record->relations, true));
        // Log::debug(print_r($record['fillable'], true));
        // Log::debug($record->fillable);
        // Log::debug($record['fillable']);
        // 更新処理
        for ($i = 0, $iMax = count($request->selectedCourses); $i < $iMax; $i++) {
            $courseData = $request->get('selectedCourses')[$i];
            // 指定のロードマップIDのn番目かのロードマップ情報があるかを確認し、新規作成するか更新する
            /* updateOrCreateの動作に不具合がある
             * Course::updateOrCreate([
                [
                    "record_id" => $id,
                    "record_index" => $i,
                ],
                とした時、SQLSTATE[42S22]: Column not found: 1054 Unknown column 'Poti3aCNw6s6xKQt' in 'where clause' (SQL: select * from `courses` where (`Poti3aCNw6s6xKQt` = 0 /course/rails-kj/ ...
                のようなずれ込んだSQL分が発行されてしまう
             */
            // Course::updateOrCreate([
            //     [
            //         "record_id" => $id,
            //         "record_index" => $i,
            //     ],
            //     [
            //         'course_id' => $courseData['id'],
            //         'title' => $courseData['title'],
            //         'instructor' => $courseData['instructor'],
            //         'url' => $courseData['url'],
            //         'image_url' => $courseData['image_url'],
            //         'description' => $courseData['description']
            //     ]
            //   ]);
            
            // リレーションでindexが存在するかにより更新かcourseロードマップの新規作成かを分ける
            if(!isset($record->courses[$i])) {
              Log::debug('更新処理にあたり追加された項目です');
              // contentsテーブルへ本文を格納
                $course = Course::create([
                    'record_id' => $id,
                    'record_index' => $i,
                    'course_id' => $courseData['course_id'],
                    'title' => $courseData['title'],
                    'instructor' => $courseData['instructor'],
                    'url' => $courseData['url'],
                    'image_url' => $courseData['image_url'],
                    'description' => $courseData['description']
                ]);
            }else{
                // indexの更新処理のみロードマップを取得しupdateする(新規作成時のSQL発行を抑えられる)
                // TODO N+1は大丈夫か？
                Log::debug('indexの更新をします');
                $course = Course::where('record_id', $id)->where('record_index', $i)->first();
                $course->update([
                    'course_id' => $courseData['course_id'],
                    'title' => $courseData['title'],
                    'instructor' => $courseData['instructor'],
                    'url' => $courseData['url'],
                    'image_url' => $courseData['image_url'],
                    'description' => $courseData['description']
                ]);
            }
        }
        // 更新後、オーバーしたindexのコースロードマップは削除する
        Course::where('record_id', $id)
            ->where('record_index', '>', count($request->selectedCourses) - 1)
            ->delete();
  
        session()->flash('session_success', '更新が完了しました');
        return response([], 200);
    }
    
    // ==============
    // ロードマップ詳細の取得
    // ==============
    public function show(string $id, bool $owner_flg = false) {
        Log::debug('==================================');
        Log::debug('ロードマップ詳細取得/ID:'.$id);
        Log::debug('==================================');
        // IDに合致するロードマップ情報を取得
        $record = Record::where('id', $id)
            ->with(['owner', 'courses', 'comments.author'])
            ->where('delete_flg', false)
            ->first();
  
        // ロードマップを返すが、存在しない場合は404を返す
        if ($record ===  null) {
          Log::debug('存在しないか、削除されています');
          return abort(404);
        }
        // ロードマップの所持者かを確認し、違うなら403を返す(Edit時のみ)
        if ($owner_flg) {
          if (Auth::user()->id !== $record->user_id) {
            return abort(403);
          };
        }
        return $record;
    }
    
    // ==============
    // ロードマップ一覧の取得
    // ==============
    public function get_list($user_id = null) {
        Log::debug('==============');
        Log::debug('ロードマップ一覧取得');
        Log::debug('==============');
        // ユーザーIDがない場合、全てのロードマップを取得する
        if ($user_id === null) {
            Log::debug('USER_ID:'.$user_id);
            $records = Record::with(['owner'])
                ->where('delete_flg', 0)
                ->orderBy(Course::CREATED_AT, 'desc')
                ->get();
      
            return $records;
        }
        // ユーザーIDがある場合、そのユーザーが投稿したロードマップに絞って取得する
        $records = Record::where('user_id', $user_id)
            ->with(['owner'])
            ->where('delete_flg', 0)
            ->orderBy(Record::CREATED_AT, 'desc')
            ->get();
        
        return $records;
    }
    
    // ==============
    // ロードマップの削除
    // ==============
    public function delete(string $id) {
        Log::debug('===============================');
        Log::debug('ロードマップ削除/ID:'.$id);
        Log::debug('===============================');
  
        // 現在認証中のユーザーの投稿済みロードマップの中に指定したIDのロードマップがあれば取得する
        $record = Auth::user()->records()->where('id', $id)->first();
        
        if ($record) {
            Log::debug('delete_flgをtrueにします');
            // delete_flgをtrueにする
            $record->update([
                'delete_flg' => true,
            ]);
            Log::debug($id.'のdelete_flgをtrueにし、論理削除されました。');
            session()->flash('session_success', '削除が完了しました');
            return response([], 200);
        }
        Log::debug('ロードマップはありませんでした');
        session()->flash('session_error', 'ロードマップが見つかりませんでした');
        return abort(404);
    }
    
    // ==============
    // コメントの投稿
    // ==============
    public function addComment(Record $record, StoreComment $request) {
        Log::debug('===========');
        Log::debug('コメント投稿');
        Log::debug('===========');
        // 投稿対象となるロードマップの存在確認
        $id = $record->id;
        $checkExistRecord = Record::where('id', $id)->where('delete_flg', false)->first();
        if ($checkExistRecord === null) {
          Log::debug('ロードマップは削除されています');
          session()->flash('session_error', 'ロードマップは削除されています');
          return abort(404);
        }
        
        Log::debug('コメントを投稿します');
        $comment = new Comment();
        $comment->content = $request->get('content');
        $comment->user_id = Auth::user()->id;
        $record->comments()->save($comment);
        
        // authorリレーションロードようにコメントを取得し直す
        $new_comment = Comment::where('id', $comment->id)->with('author')->first();
  
        session()->flash('session_success', 'コメントが投稿されました');
        return response($new_comment, 201);
    }
}
