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
use DB;


class SampleController extends Controller
{

    public function __construct(){
    }

    public function bengBang(Request $request){

        DB::table('comments')->insert(
            [
                'body' => 'john@example.com2', 
                'post_id' => 0,
                'user_id' => 0
            ]
        );

        $pusher = App::make('pusher');
        $pusher->trigger( 'test-channel',
                          'test-event', 
                          array('text' => 'hey'));

        return json_encode("hey");

    }

    public function getSession(Request $request){
        return "qwe";
    }



}
