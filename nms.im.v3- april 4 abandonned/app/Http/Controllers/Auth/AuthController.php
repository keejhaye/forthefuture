<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

use App\Models\TblUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests;
use Session;
use Redirect;
use Cache;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware($this->guestMiddleware(), ['except' => 'auth/logout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    /**
     * FOLLWING LINES WERE COPIED FROM Controllers/AuthController.php
     */

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

        $user_stat = array("outbound_count" => $count_result[0]['count'], "chat_time" => $avg);
        \Redis::set('user_stat:'.$user->id, json_encode($user_stat));
        \Redis::set('user_services:'.$user->id, json_encode($user_session['services']));
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
    
    public function logout(Request $request) {
        header('Cache-Control: no-cache');
        header('Pragma: no-cache');
        Cache::flush();

        $user_id = session('user.id');
        if($user_id == NULL)
            return Redirect::to('auth/login');

        $conversations = \TblConversationDuration::select("id", "conversation_id")
            ->where("user_id", $user_id)
            ->where("status","LIKE","ongoing%")
            ->get();

         $user = \TblUsers::find($user_id);
        
        if(!$conversations->isEmpty()){
            $post['details']['conversations'] = $conversations->toArray();
        }  
        $post['details']['action'] ='logout';
        $post['details']['user_id'] = $user_id;
        $post['details']['username'] = $user->username;
        \Redis::publish('update-data', json_encode($post['details']));

         $logged_in = \TblLoggedInUsers::whereUserId($user_id)->first();

         if($logged_in != NULL){
            $logged_in->delete();
         }

        \LogHelper::log_user_activity_modified("logout", "user logged out", $user_id);
        \LogHelper::log_user_activity("logout", $user_id);

        $request->session()->flush();
        Auth::logout();
        Session::flush();

        \Redis::del('user_stat:'.$user_id);
        \Redis::del('user_services:'.$user_id);
        \Redis::decr('online_count');
        return Redirect::to('/');
    }

    public function forgot_password() {
    }
}
