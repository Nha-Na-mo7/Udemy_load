<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MypageController extends Controller
{
  // ビューを返却
  public function index()
  {
    return view('pages.mypage');
  }
}
