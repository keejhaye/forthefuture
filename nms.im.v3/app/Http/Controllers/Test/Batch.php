<?php

namespace App\Http\Controllers\Test;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class Batch extends Controller {

public function index(){
 return view('test/BatchContent')->with('page_title', 'Batch Assign');
}
}