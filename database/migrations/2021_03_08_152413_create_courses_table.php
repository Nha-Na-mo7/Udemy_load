<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
            $table->string('record_id')->comment('紐付けられたロードマップID');
            $table->unsignedInteger('record_index')->comment('ロードマップのn番目のコース');
            $table->String('course_id')->comment('Udemyが振り分けたコースごとのID');
            $table->string('title')->comment('コースのタイトル');
            $table->string('instructor')->comment('講師名');
            $table->text('description')->comment('コースごとの説明');
            $table->text('url')->comment('コースのURL');
            $table->text('image_url')->comment('コースの画像URL');
            $table->timestamps();
        });
        // ロードマップIDは大文字小文字を区別する
        DB::statement('ALTER TABLE courses MODIFY record_id varchar(256) BINARY');
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
