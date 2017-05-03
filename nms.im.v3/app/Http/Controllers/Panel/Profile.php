<?php

namespace App\Http\Controllers\Panel;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class Profile extends Controller {

    public function index() {
        return view('panel/ProfileContent')->with('page_title', 'Profile');
    }

    public function user() {

        $user_id = session('user.id');
        return \TblUsers::leftJoin('tbl_roles', function($join) {
                            $join->on('tbl_roles.id', '=', 'tbl_users.role_id');
                        })
                        ->where('tbl_users.id', '=', $user_id)
                        ->first();
    }

    public function show($id) {
        return \TblUsers::find($id);
    }
    /**
     * Update the specified resource in storage.p
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request) {
         $id = session('user.id');
          $user_old = \TblUsers::find($id);

        $user = \TblUsers::find($id);
        $user->username = $request->input('username');
        $user->firstname = $request->input('firstname');
        $user->lastname = $request->input('lastname');
        
        if(!$user->passwordencrypt == NULL){
            $user->passwordencrypt = null;
        }
         
        if($user_old->password != $request->input('password')){
                 if($request->input('password_confirm') != $request->input('password')){
                        return "Password did not match";
                        die('password unmatch');
                    }else{
                         $password = sha1($request->input('password'));
                         $user->password = $password;
                    }    
        }
        $user->email = $request->input('email');
        $user->role_id = $request->input('role_id');
        $user->status = $request->input('status');
        $user->date_created = gmdate('Y-m-d H:i:s');
        $user->save(); 
        return 0;
    }

    public function get_services(){
        $service_ids = session('user.services');

        $services = \TblServices::whereIn('id',$service_ids)
         ->select(\DB::raw('name'))
         ->get();

         return $services;
    }
}
