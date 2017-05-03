<?php

namespace App\Http\Controllers\Panel\Routine;

use Illuminate\Http\Request;
use DateTime;
use DateTimezone;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Session;
use Redirect;

class Users extends Controller
{
  public function kick_inactive_users() {
    $users = \TblLoggedInUsers::get();
    $cnt = 0;

    if ($users->count() > 0) {
      foreach ($users as $key => $user) {
        $sec = \Config::get("application.default_inactive_users_timeout");
        $date_parked = \DateTimeHelper::get_date("Y-m-d H:i:s", $user->time_parked, null);

        $date_kick = gmdate('Y-m-d H:i:s', strtotime($date_parked . '+' . $sec . 'seconds'));

        if ($date_kick <= $user->last_ping) {
          $user->delete();
          $cnt++;
        }
      }
    }
    return $cnt;
  }

   protected function update_last_ping(){
    $user_id = session('user.id');
    $logged_in_user = \TblLoggedInUsers::where('user_id', $user_id)->first();
    if($logged_in_user){
      $logged_in_user->last_ping = gmdate("Y-m-d H:i:s");
      $logged_in_user->save();

      return $logged_in_user->last_ping;
    }else{
      return Redirect::to('auth/logout');
  }
    }

}