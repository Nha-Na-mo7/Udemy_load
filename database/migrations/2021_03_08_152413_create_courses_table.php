<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('record_id')->comment('紐付けられたレコードID');
            $table->unsignedInteger('record_index')->comment('レコードのn番目のコース');
            $table->String('course_id')->comment('Udemyが振り分けたコースごとのID');
            $table->string('title')->comment('コースのタイトル');
            $table->string('instructor')->comment('講師名');
            $table->text('description')->comment('コースごとの説明');
            $table->text('url')->comment('コースのURL');
            $table->text('image_url')->comment('コースの画像URL');
            $table->timestamps();
  
            // recordsテーブルのidと紐付け
            $table->foreign('record_id')->references('id')->on('records');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('courses');
    }
}
