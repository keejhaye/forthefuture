<?php

namespace App\Http\Controllers\Mock\Dev;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Status extends Controller
{

    public function index(){
        return view('mock.dev.chatStatus');
    }
    public function all(){
        return view('mock.dev.devStatus');
    }

}
