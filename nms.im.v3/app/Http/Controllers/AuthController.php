<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

use App\Models\TblUsers;
use App\Http\Requests;
use Session;
use Validator;
use Redirect;
use Cache;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;


class AuthController extends Controller
{
    public function index() {
        if(Auth::check()){
            if(session()->has('user')){
                return Redirect::to('panel/park');
            }
        }

        return view('LoginContent');
    }

    public function authenticate(Request $request) {
        //do token comparison before validation
        $token = $request->input('_token');
        $session_token = session()->getToken();
        if($token == $session_token){
            $request->replace(array(
                '_token' => $session_token,
                'username' => $request->input('username'),
                'password' => $request->input('password')
            ));
        }
        
        $validator = Validator::make ( $request->all(), ['username' => 'required', 'password' => 'required'] );

        if($validator->fails()){
            return $this->auth_fail();
        }else{
            $user = TblUsers::where('username' , $request->input('username'))->first();
            $password = $request->input('password');
            
            if($user != null){
                if($user->passwordencrypt != null){
                    return $this->login($request->input('username'), $password, $user);
                }else{
                    if($user->password == sha1($password)){
                        $user->passwordencrypt = bcrypt($password);
                        $user->remember_token = $request->input('_token');
                        $user->save();
                        
                        return $this->login($request->input('username'), $password, $user);
                    }else{
                        return $this->auth_fail();
                    }
                }
            }else{
                return $this->auth_fail();
            }            
        }
    }

    /**
     * Use this after September
     * This removes the checking of v2's password which is using sha1 hash
     * Notifies user to contact admin if it has not acquired a brypted password
     */
   // public function authenticate(Request $request) {
   //     $validator = Validator::make ( $request->all(), ['username' => 'required', 'password' => 'required'] );
       
   //     if($validator->fails()){
   //         return $this->auth_fail();
   //     }else{
   //         $user = TblUsers::where('username' , $request->input('username'))->first();
   //         $password = $request->input('password');
           
   //         if($user != null){
   //               if($user->passwordencrypt == null){
   //                   Session::flash ( 'message', "Contact admin to acquire password!" );
   //               return Redirect::back();
   //               }

            //  return $this->login($request->input('username'), $password, $request->input('password'));
   //         }else{
   //               return $this->auth_fail();
   //         }            
   //     }
   // }

    protected function auth_fail() {
        \Session::flash ( 'message', "Invalid username or password!" );
        return Redirect::back();
    }

    protected function auth_success(TblUsers $user) {
        $user_session = [
            'id'        => $user->id,
            'username'  => $user->username,
            'email'     => $user->email,
            'role_id'   => $user->role_id,
            'firstname' => $user->firstname,
            'lastname'  => $user->lastname,
            'status'    => $user->status,
            'services'  => \TblUserService::get_user_services($user->id)
        ];

        session(['user' => $user_session]);
        $count_result = \RptMessages::count_outbound($user->id);
        $avg_response = \RptMessages::operator_avg_response($user->id);
        \Session::set('outbound_count', $count_result[0]['count']);

        $avg = gmdate("H:i:s", $avg_response[0]['total_duration']);
        \Session::set('chat_time',$avg);
        \Redis::incr('online_count');
        \LogHelper::log_user_activity("parked", $user->id);
        \LogHelper::log_user_activity_modified("login", "user logged in", $user->id);

        return Redirect::to('panel/park');
    }

    protected function login($username, $password, TblUsers $user) {
        if (Auth::attempt(['username' => $username, 'password' => $password, 'status' => 'active'])) {
            $user->logins ++;
            $user->last_login = gmdate('Y-m-d H:i:s');
            $user->save();
            $check_logged_in = \TblLoggedInUsers::whereUserId($user->id)->first();
            if(!$check_logged_in){
                  $logged_in = new \TblLoggedInUsers();
            $logged_in->user_id = $user->id;
            $logged_in->status = 'parked';
            $logged_in->time_parked = gmdate('Y-m-d H:i:s');
            $logged_in->url =  $_SERVER['REMOTE_ADDR'];
            $logged_in->save();
            }
           //  Session::flush();
            Cache::flush();
                     
            return $this->auth_success($user);
        } else {
            return $this->auth_fail();
        }
    }
    
    public function logout() {
        // die(var_dump(session()->all()));

        $user_id = session('user.id');
        $conversations = \TblConversationDuration::select("id", "conversation_id")
            ->where("user_id", $user_id)
            ->where("status","LIKE","ongoing%")
            ->get();

        if(!$conversations->isEmpty()){
            $post['details']['conversations'] = $conversations->toArray();
            $post['details']['action'] ='logout';
            $post['details']['user_id'] = $user_id;
           \Redis::publish('update-data', json_encode($post['details']));
        }  

         $logged_in = \TblLoggedInUsers::whereUserId($user_id)->first();

         if($logged_in != NULL){
            $logged_in->delete();
         }

        \LogHelper::log_user_activity_modified("logout", "user logged out", $user_id);
        \LogHelper::log_user_activity("logout", $user_id);

        Auth::logout();
        Session::flush();
        Cache::flush();

        session()->flush();
        session()->save();

        \Redis::decr('online_count');
        return Redirect::to('/');

        // if(Auth::check() == false){
        //     \Redis::decr('online_count');
        //     return Redirect::to('login');
        // }
        // else{
        //     Auth::logout();
        //     session()->flush();
        //     Cache::flush();

        //     \Redis::decr('online_count');
        //     return Redirect::to('login');            
        // }
    }

    public function forgot_password() {
    }
}
