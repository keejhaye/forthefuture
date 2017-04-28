<?php

namespace App\Http\Controllers\Panel;

use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Redirect;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;
header('Cache-Control: no-cache');
header('Pragma: no-cache');
class Park extends Controller {
    public function index(){

    }

    public function park_user(){
        $user_id = session('user.id');
        // $this->check_if_logged_in($user_id);
        
        $conversations = \TblConversationDuration::select("id", "conversation_id")
                        ->where("user_id", $user_id)
                        ->where("status","LIKE","ongoing%")
                        ->get();

        $user = \TblUsers::find($user_id);
        
        if(!$conversations->isEmpty()){
            $post['details']['conversations'] = $conversations->toArray();
        }  
        $post['details']['action'] ='park';
        $post['details']['user_id'] = $user_id;
        $post['details']['username'] = $user->username;
        \Redis::publish('update-data', json_encode($post['details']));

        $logged_in = \TblLoggedInUsers::whereUserId($user_id)->first();
        if($logged_in){
            $stat_socket = json_decode(\Redis::get('stat_socket:'.$user_id), 1);

            if($stat_socket == null || (isset($stat_socket['onPark']) &&  $stat_socket['onPark'] == 0))
                $logged_in->time_parked = gmdate('Y-m-d H:i:s');
        
            $logged_in->chat_time = (NULL);
            $logged_in->status = 'parked';
            $logged_in->save();
        }
        
        return view('panel/ParkContent');
    }

    public function check_if_logged_in($user_id){
         $logged_in = \TblLoggedInUsers::whereUserId($user_id)->first();

         if(!$logged_in){
            session()->flush();
            return Redirect::to('login');
         }

    }
}
