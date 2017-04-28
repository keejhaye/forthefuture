<?php

namespace App\Http\Controllers\Mock\Dev;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Permissions extends Controller
{
    public function overview(){
	    $permissions = config("permissions.permissions");

	    $roles = \Redis::hgetall('role_names');
	    if($roles == null) die("no groups");

	    return view('mock/dev/permissions', ["groups" => $roles, "permissions" => $permissions, "page_title" => "Permissions Overview"]);
	}

	public function process_overview(Request $request){
		$group = \TblRoles::find($request->input("group_id"));

		$permission = $request->input("permission");
		$permission_name = str_replace("_", " ", $permission);

		if($group && !empty($permission)){
			switch($request->input("set_flag")){
				case "green":
					// echo "giving permission...";
					$this->add_permission($group, $permission);

					$log = "{$group->name}: [+] {$permission_name}";
					break;
				case "red":
					// echo "removing permission...";
					$this->remove_permission($group, $permission);

					$log = "{$group->name}: [-] {$permission_name}";
					break;
			}
		}

		echo $log;
	}

	protected function add_permission(\TblRoles $group, $permission){
		$permissions_array = json_decode($group->permissions, true);
	    $permissions_array[$permission] = 1;

	    $group->permissions = json_encode($permissions_array);
	    $group->save();

	    // Adds permission to Redis Set
	    \Redis::sadd('role_permissions:'.$group->id, $permission);
	}

	protected function remove_permission(\TblRoles $group, $permission){
		$permissions_array = json_decode($group->permissions, true);
	    unset($permissions_array[$permission]);
	    
	    $group->permissions = json_encode($permissions_array);
	    $group->save();

	    // Removes permission on Redis Set
	    \Redis::srem('role_permissions:'.$group->id, $permission);
	}
}
