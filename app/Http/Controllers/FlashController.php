<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FlashController extends Controller
{
    //
  public function flash(Request $request)
  {
    $request->getSession()->flash('key', 'value1');
    $request->getSession()->flashInput(array_merge($request->input(), ['key' => 'value2']));
    
    return view('flash');
  }
  
  public function flash2(Request $request)
  {
    return view('flash2');
  }
}
