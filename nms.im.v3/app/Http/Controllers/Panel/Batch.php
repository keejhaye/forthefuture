<?php

namespace App\Http\Controllers\Panel;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class Batch extends Controller {

    public function index() {
        return view('panel/BatchContent')->with('page_title', 'Batch Assign');
    }



}
