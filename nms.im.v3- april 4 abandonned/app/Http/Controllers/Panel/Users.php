<?php

namespace App\Http\Controllers\Panel;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class Users extends Controller {

    public function index() {
        return view('panel/UsersContent')->with('page_title', 'Users');
    }

    public function users($id = null) {
            if ($id == null) {
            return \TblUsers::orderBy('id', 'asc')->get();
        } else {
            return $this->show($id);
            return $role_id = session('user.role_id');
        }
    }

     public function show($id) {
        return \TblUsers::find($id);
    }
      public function add(Request $request) {
        $exist = \TblUsers::where('username', $request->input('username'))->first();

        if($exist){
            return("User exist");
        }else{
             $users = new \TblUsers;
     
        $users->username = $request->input('username');
        $users->firstname = $request->input('firstname');
        $users->lastname = $request->input('lastname'); 
          if($request->input('password_confirm') != $request->input('password')){
                        return "Password did not match";
                        die('password unmatch');
                    }else{
                         $password = sha1($request->input('password'));
                         $users->password = $password;
                    }    
        $users->email = $request->input('email');
        $users->role_id = $request->input('role_id');
        $users->status = $request->input('status');
        $users->date_created = gmdate('Y-m-d H:i:s');
        
        $users->save();
        $old_data = "-";
        $new_data = $users->toArray();    
        \LogHelper::user_activity("users", $users->username, 'add', $old_data, $new_data);   
        
        $params = array(
            "id" => $users->id,
            "status" => 0
            );
        return $params;
        }


       
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id) {
        $user_old = \TblUsers::find($id);
        $old_data = $user_old->toArray();

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
        $user->save();

         $new_data = $request->input();    
        \LogHelper::user_activity("users", $user->username, 'update', $old_data, $new_data);   
        $params = array(
            "id" => $user->id,
            "status" => 0
            );
        return $params;
    }

}
