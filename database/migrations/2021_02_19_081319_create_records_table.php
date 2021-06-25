<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('records', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->unsignedBigInteger('user_id')->comment('投稿者ID');
            $table->string('title')->comment('投稿タイトル');
            $table->text('description')->comment('概要・説明');
            $table->boolean('delete_flg')->default(false)->comment('削除フラグ');
            $table->timestamps();
            
            // usersテーブルのidと紐付け
            $table->foreign('user_id')->references('id')->on('users');
        });
        // ロードマップIDは大文字小文字を区別する
        DB::statement('ALTER TABLE records MODIFY id varchar(256) BINARY');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('records');
    }
}
