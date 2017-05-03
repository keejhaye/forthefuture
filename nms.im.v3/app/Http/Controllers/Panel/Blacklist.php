<?php

namespace App\Http\Controllers\Panel;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class Blacklist extends Controller {

    public function index() {
        return view('panel/BlacklistContent')->with('page_title', 'Blacklist');
    }

    public function subscribers($id = null) {
        if ($id == null) {
  $query = \App\Models\TblBlacklist::select(\DB::raw('tbl_blacklist.subscriber_id as id,s.name as subscriber'))
                      ->leftJoin('tbl_subscribers AS s', 's.id', '=', 'tbl_blacklist.subscriber_id')
                      ->leftJoin('tbl_services AS ss', 'ss.id', '=', 's.service_id')
                      ->leftJoin('tbl_users AS u', 'u.id','=', 'tbl_blacklist.user_id');         
        return $query->get();
        } else {
            return $this->show($id);
        }
    }

    public function show($id) {
          $query = \App\Models\TblBlacklist::where('tbl_blacklist.subscriber_id', $id)
                      ->select(\DB::raw('tbl_blacklist.subscriber_id as id,s.name as subscriber, ss.name as service, u.username as operator, tbl_blacklist.date_created'))
                      ->leftJoin('tbl_subscribers AS s', 's.id', '=', 'tbl_blacklist.subscriber_id')
                      ->leftJoin('tbl_services AS ss', 'ss.id', '=', 's.service_id')
                      ->leftJoin('tbl_users AS u', 'u.id','=', 'tbl_blacklist.user_id');       
        return $query->first();
    }

    public function add(Request $request) {
        $users = new \TblUsers;

        $users->username = $request->input('username');
        $users->firstname = $request->input('firstname');
        $users->lastname = $request->input('lastname');
        $users->passwordencrypt = bcrypt($request->input('password'));
        $users->password = sha1($request->input('password'));
        $users->email = $request->input('email');
        $users->role_id = $request->input('role_id');
        $users->status = $request->input('status');
        $users->date_created = gmdate('Y-m-d H:i:s');

        $users->save();

        return 'Success';
    }

    public function destroy($id) {
         $subscriber = \TblSubscribers::find($id);
          $subscriber->status = "active";
          $subscriber->save();

        $blacklist =\TblBlacklist::where('tbl_blacklist.subscriber_id', $id)->first();
        $blacklist->delete();

        return 0;
    }

}
