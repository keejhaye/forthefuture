<?php

namespace App\Http\Controllers\Panel;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class OnlineUsers extends Controller {

    public function index() {
        return view('panel/OnlineUsersContent')->with('page_title', 'Online Users');
    }

      public function fetch() {
          
            return \TblLoggedInUsers::fetch_online_users();
        
    }

    public function kick($id){
      $user_id = session('user.id');
       $conversations = \TblConversationDuration::select("id")
                        ->where("user_id", $id)
                        ->where("status","LIKE","ongoing%")
                        ->get();
     if(!$conversations->isEmpty()){
        $post['details'] = $conversations->toArray();
        $post['details']['action'] ='logout';
        $post['details']['user_id'] = $id;
       \Redis::publish('update-data', json_encode($post['details']));
     }  

     $logged_in = \TblLoggedInUsers::whereUserId($id)->first();
     $logged_in->delete();
      \LogHelper::log_user_activity_modified("logout", "user kicked by", $user_id);

      // unmap

      \LogHelper::log_user_activity("logout", $id);

      // logout TblLoggedInUsers

   //   session()->flush();
     //   Auth::logout();
        //return Redirect::to('login');
    }

    public function get_services(){
      return \TblServices::get_services();
    }

}
