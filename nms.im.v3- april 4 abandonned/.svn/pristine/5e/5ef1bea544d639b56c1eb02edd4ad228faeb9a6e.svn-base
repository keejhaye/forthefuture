<?php

namespace App\Http\Controllers\Test;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class SampleTestController extends Controller
{
    public function redis()
	{
		$redis = \Redis::connection();
		
		$redis->set('name', 'Taylor');
		$name = $redis->get('name');
		$values = $redis->lrange('names', 5, 10);

		echo '<pre>';
		print_r($name);
		print_r($values);
		echo '</pre>';
	}

	public function tblusers()
	{
		$user = \App\Models\TblUsers::find(2)->tblRoles;

		return $user;
	}
	
	public function accounts_api_helper()
	{
		$user = \App\Helpers\AccountsApiHelper::log_inbound_request('post', 'url', 'ip');

		echo $user;
	}

	public function login()
	{
		return view('login');
	}

	public function hashing(){
		var_dump(sha1('qwerty'));
		return hash('sha1', 'qwerty');
	}

	public function loggedin(){
		var_dump("Success Logged");
	}

	public function other_function(){
		return $this->called_function();
	}

	private function called_function(){
		return "good";
	}

	public function log_user_activity_modified(){
		var_dump(session('user.id'));
		\LogHelper::log_user_activity("parked", session('user.id'));
		\LogHelper::log_user_activity_modified("login", "user logged in", session('user.id'));
	}

	public function add_user_log(){
		var_dump(session('user.id'));
		// \TblUserLogs::add_log(session('user.id'), 'parked');
		\TblUserSystemLogs::add_log(session('user.id'), 'login', null);
	}

	public function get_role_name(){
		$role_name = \Redis::hget('role_names', 2);
		var_dump($role_name);
	}

	public function get_set_cookie(){
		/* set cookie */
		// \Cookie::queue('tz', '4.0');
		// return;

		/* forget cookie */
		// \Cookie::queue(
		//     \Cookie::forget('tz')
		// );
		// return;

		/* test set_timezone */
		// $settz = \DateTimeHelper::set_timezone('10.0');
		// var_dump($settz);

		/* test get_timezone */
		$gettz = \DateTimeHelper::get_timezone();
		var_dump($gettz);

		return \DateTimeHelper::get_timezone_by_offset($gettz);
	}
}
