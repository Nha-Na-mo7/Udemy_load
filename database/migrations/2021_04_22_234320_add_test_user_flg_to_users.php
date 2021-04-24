<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTestUserFlgToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      // テストユーザーかを判定するカラム。基本的にfalse。
      Schema::table('users', function (Blueprint $table) {
        $table->boolean('test_user_flg')->default(false)->after('delete_flg')->comment('テストユーザーか');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
          $table->dropColumn('test_user_flg');
        });
    }
}
