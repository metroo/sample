<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class testController extends Controller
{
    //
       public function display_view($value='')
       {
           $test = \App\test::find(1);
           //dd($test);
           $type = $test->name;
           return view('tests.test' , compact('type'));
       }
}
