<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Chat;
use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Support\Facades\App;
use App\Flight;
use App\Flighttwo;
use Session;
use Carbon\Carbon;

class SampleController extends Controller
{

    public function __construct(){
    }

    public function bengBang(Request $request){

        $pusher = App::make('pusher');

        $pusher->trigger( 'test-channel',
                          'test-event', 
                          array('text' => 'Preparing the Pusher Laracon.eu workshop!'));


        return json_encode($request->input());

    }



}
