<?php

namespace App\Http\Controllers\Panel;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class UserActivity extends Controller {

    public function index() {
        return view('panel/UserActivityContent')->with('page_title', 'User Activity Logs');
    }

    public function logs($id = null) {
            if ($id == null) {
          $query = \App\Models\TblUserActivities::select(\DB::raw('tbl_user_activities.*, u.username'))
                      ->leftJoin('tbl_users AS u', 'u.id', '=', 'tbl_user_activities.user_id');     
        return $query->get();
        } else {
            return $this->show($id);
        }
    }

 


}
