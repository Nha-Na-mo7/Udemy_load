<?php

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      // 開発用ユーザーを定義
      User::create([
          'name' => 'testuser',
          'email' => 'testuser@example.com',
          'password' => Hash::make('testtest'),
          'test_user_flg' => true,
      ]);
    }
}
