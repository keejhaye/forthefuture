<?php

namespace App\Http\Controllers\Panel;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\ProtectedPageController;
use App\Models\TblUserService;

class UserContext extends ProtectedPageController
{
    // public function __construct() {
    //     if(! \Redis::sismember('group_permissions:'.session('user.group_id'), 'section_contexts'))
    //         abort(404);
    // }

    public function index(){
        die('!');
    }

    public function search(Request $request) {
     
        $params = [
            'level' => \Redis::hget('group:'.session('user.group_id'), 'level'),
            'user_level_operation' => '<=',
            'active' => 1
        ];

        if(isset($request->keyword))
            $params['keyword'] = $request->keyword;

        $query = \App\Models\TblUsers::search_user($params)
                  ->select(\DB::raw('u.id,u.username, concat(u.firstname," ",u.lastname) as name'))
                  ->leftJoin('tbl_user_service AS us', 'us.user_id', '=', 'u.id')
                  ->where(function ($query) use ($request){
                      $query->where('us.service_id','!=', $request->service_id)
                            ->orWhereNull('us.id');
                    })
                  ->take(15);

        return ['data'=>$query->get()];
    }

 public function search_service(Request $request) {
        $params = [
            'level' => \Redis::hget('group:'.session('user.group_id'), 'level'),
            'user_level_operation' => '<=',
            'active' => 1
        ];

        if(isset($request->keyword))
            $params['keyword'] = $request->keyword;

        $query = \App\Models\TblServices::search($params)
                  ->select(\DB::raw('s.id,s.name as name'))
                  ->leftJoin('tbl_user_service AS us', 'us.user_id', '=', 'u.id')
                  ->take(16);

        return ['data'=>$query->get()];
    }
 public function search_users(Request $request) {
        $params = [
            'level' => \Redis::hget('group:'.session('user.group_id'), 'level'),
            'user_level_operation' => '<=',
            'active' => 1
        ];

        if(isset($request->keyword))
            $params['keyword'] = $request->keyword;

        $query = \App\Models\TblServices::search($params)
                  ->select(\DB::raw('s.id,s.name as name'))
                  ->leftJoin('tbl_user_service AS us', 'us.service_id', '=', 's.id')
                  ->take(16);

        return ['data'=>$query->get()];
    }
    public function search_assigned(Request $request){
        $query = TblUserService::search($request->all())->select(\DB::raw('us.id,u.username, concat(u.firstname," ",u.lastname) as name, g.name as role'));
        
        return $query->get();
    }

    public function user_services($id){
        $query = TblUserService::where('user_id', $id)->leftJoin('tbl_services', 'tbl_services.id', '=', 'tbl_user_service.service_id')->select('tbl_services.name', 'user_id','service_id');
        
        return $query->get();
    }

       public function service_users($id){
        $query = TblUserService::where('service_id', $id)->leftJoin('tbl_users', 'tbl_users.id', '=', 'tbl_user_service.user_id')->select('tbl_users.username', 'user_id','service_id');
        
        return $query->get();
    }

    public function unassigned_services($id){
        $user_services = TblUserService::where('user_id', $id)->pluck('service_id')->toArray();

        $query = \App\Models\TblServices::whereNotIn('id', $user_services)->where('status','active')->select('name', 'id');
        
        return $query->get();
    }

     public function unassigned_users($id){
        $user_services = TblUserService::where('service_id', $id)->pluck('user_id')->toArray();

        $query = \App\Models\TblUsers::whereNotIn('id', $user_services)->select('username', 'id', 'role_id');
        
        return $query->get();
    }

    public function delete_assigned_services(Request $request, $id){
        $deleted = TblUserService::whereIn('service_id', $request)->where('user_id', $id)->delete();

        if($deleted > 0){
            $data = [
                'action' => 'update_user_services',
                'panel' => 'users', 
                'sub_action' => 'delete', 
                'user' => $id, 
                'services' => $_POST, 
            ];

            $user_services = \Redis::get('user_services:'.$id);
            if($user_services != null){
                $user_services = json_decode($user_services);
                foreach($data['services'] as $service){
                    if(in_array((int)$service, $user_services)){
                        $key = array_search((int)$service, $user_services);
                        array_splice($user_services, $key, 1);
                    }
                }
                \Redis::set('user_services:'.$id, json_encode($user_services));
            }

            \Redis::publish('update-data', json_encode($data));
        }

        return $deleted;
    }

    public function delete_assigned_users(Request $request, $id){
        $deleted = TblUserService::whereIn('user_id', $request)->where('service_id', $id)->delete();

        if($deleted > 0){
            $data = [
                'action' => 'update_user_services',
                'panel' => 'services', 
                'sub_action' => 'delete', 
                'service' => $id, 
                'users' => $_POST, 
            ];

            foreach($data['users'] as $user) {
                $user_services = \Redis::get('user_services:'.$user);
                if($user_services != null){
                    $user_services = json_decode($user_services);
                    $key = array_search((int)$id, $user_services);
                    array_splice($user_services, $key, 1);
                    \Redis::set('user_services:'.$user, json_encode($user_services));
                }
            }

            //update user services in node
            \Redis::publish('update-data', json_encode($data));
        }

        return $deleted;
    }

    public function assign_services(Request $request, $id){
        $services_id = json_decode($request->services);
        $data = array();
        foreach ($services_id as $key => $value) {
            $array_data = array('user_id'=>$id, 'service_id'=> $value, 'date_created'=>gmdate('Y-m-d H:i:s'));
            array_push($data, $array_data);
        }
        
        try{
            TblUserService::insert($data);

            $update_data = [
                'action' => 'update_user_services',
                'panel' => 'users', 
                'sub_action' => 'add', 
                'user' => $id, 
                'services' => $services_id, 
            ];

            $user_services = \Redis::get('user_services:'.$id);
            if($user_services != null){
                $user_services = json_decode($user_services);
                $user_services = array_merge($user_services, $services_id);                
                \Redis::set('user_services:'.$id, json_encode($user_services));
            }
            else{
                \Redis::set('user_services:'.$id, json_encode($services_id));
            }
            \Redis::publish('update-data', json_encode($update_data));
        }
        catch(Exception $e){
           // do task when error
           return $e;
        }

        return 0;
    }

    public function assign_users(Request $request, $id){
        $users_id = json_decode($request->users);
        $data = array();
        foreach ($users_id as $key => $value) {
            $array_data = array('service_id'=>$id, 'user_id'=> $value, 'date_created'=>gmdate('Y-m-d H:i:s'));
            array_push($data, $array_data);
        }
        
        try{
            TblUserService::insert($data);
            $update_data = [
                'action' => 'update_user_services',
                'panel' => 'services', 
                'sub_action' => 'add', 
                'service' => $id, 
                'users' => $users_id, 
            ];

            foreach ($data as $us) {
                $user_services = \Redis::get('user_services:'.$us['user_id']);

                if($user_services != null){
                    $user_services = json_decode($user_services);
                    array_push($user_services, (int)$us['service_id']);
                    \Redis::set('user_services:'.$us['user_id'], json_encode($user_services));
                }
                else{
                    \Redis::set('user_services:'.$us['user_id'], json_encode([(int)$us['service_id']]));
                }
            }

            \Redis::publish('update-data', json_encode($update_data));
        }
        catch(Exception $e){
           // do task when error
           return $e;
        }

        return 0;
    }

    public function save_service_users(Request $request){
        $countUsers = count($request->users);
        $is_multiple = \App\Models\TblServices::find($request->service_id)->multiple_user;
        $validator = $this->validate_service_users($request,$countUsers,$is_multiple);

        $result = [];
        if($validator->fails()){
            $result['hasError'] = 1;
            $result['errors'] = $validator->errors();
        }else{
            if($is_multiple == 0 && $countUsers == 1){
                // multiple_users is not allowed && assigned_user is only one
                // delete user_services record then assign only one user
                TblUserService::where('service_id', $request->service_id)->delete();
            }

            $result['hasError'] = 0;
            $result['notif'] = "Successfully assigned operators.";

            $params = [];
            $sqlQuery = "";
            foreach ($request->users as $user) {
                $sqlQuery .= "(?, ?, '".gmdate('Y-m-d H:i:s')."'),";
                $params[] = $user;
                $params[] = $request->service_id;
            }

            $sqlQuery = rtrim($sqlQuery,',');
            // ignores duplicate entry error
            \DB::insert("INSERT IGNORE INTO tbl_user_service (user_id, service_id, date_created) VALUES ".$sqlQuery, $params);
        }

        return $result;
    }

 
    protected function validate_service_users($request, $countUsers, $is_multiple){
        $input = [
            'service_id' => 'required',
            // 'multiplier' => 'required|numeric',
            'users' => 'required'
        ];

        if($is_multiple == 0 && $countUsers > 1)
            $input['users'] = 'required|max:1';

        $custom_error_msg = [
            'service_id.required' => 'Please select a service.',
            'users.required' => 'Please select operators.',
            'users.max' => 'Multiple user is not allowed on this service. Please select one user.'
        ];

        $validator = \Validator::make($request->all(), $input, $custom_error_msg);
        
        return $validator;
    }

    public function delete(Request $request){
        $del = TblUserService::destroy($request->id);
        return $del;
    }

    // canned
    public function get_canned_messages($filter){
        $query = \App\Models\TblServices::find($filter)->tblCannedMessages();
        
        return $query->get();
    }
    
    public function save_canned_message(Request $request, $id){
        // if(is_null($request->label) || is_null($request->message) || empty($request->label) || empty($request->message) ){
        //     return 1;
        // }
            $canned = new \App\Models\TblCannedMessages;
            $canned->service_id = $id;
            $canned->label = $request->label;
            $canned->message = $request->message;
            $canned->date_created = gmdate('Y-m-d H:i:s');
            $canned->save();
        return 0;
    }
    
    public function delete_canned_message(Request $request){
        $del = \App\Models\TblCannedMessages::where('id', $request->id)->delete();
        return $del;
    }


    //auto reminder
    public function get_libraries($filter){
        $query = \App\Models\tblLibraries::get();
        return $query;
    }
    
    public function get_auto_reminders($id){
        $query = \App\Models\TblServices::find($id)->tblAutoreminders()->with('tblLibraries');
        return $query->get();
    }

    public function delete_auto_reminder(Request $request){
        $del = \App\Models\TblAutoreminders::where('id', $request->id)->delete();
        return $del;
    }

    public function save_auto_reminder(Request $request, $id){
        // if(is_null($request->library) || empty($request->library) || empty($request->idle) || is_null($request->idle) || empty($request->timezone) || is_null($request->timezone) ){
        //     return 1;
        // }

        $reminder = new \App\Models\TblAutoreminders;
        $reminder->service_id = $id;
        $reminder->library_id = $request->library;
        $reminder->idle_time = $request->idle;
        $reminder->schedule = $request->schedule;
        $reminder->timezone = $request->timezone;
        $reminder->date_created = gmdate('Y-m-d H:i:s');
        $reminder->save();

        return 0;
    }

    //auto responder
    public function get_personas($filter){
        $query = \App\Models\TblServices::find($filter)->tblPersonas();
        return $query->get();
    }



    public function get_rules($id){
            $query = \App\Models\TblServices::where('tbl_services.id', $id)
                      ->select(\DB::raw('au.id, au.service_id,au.message_condition,au.delay,au.keyword,lib.name as library,perso.name as persona'))
                      ->leftJoin('tbl_autoresponses AS au', 'au.service_id', '=', 'tbl_services.id')
                      ->leftJoin('tbl_libraries AS lib', 'lib.id', '=', 'au.library_id')
                      ->leftJoin('tbl_personas AS perso', 'perso.id', '=', 'au.persona_id');         
        return $query->get();
    }
    public function get_rule_by_id($ruleId){
        $query= \App\Models\TblAutoresponses::find($ruleId);
        return $query;
    }

    public function save_rule(Request $request, $id){
        // if(is_null($request->condition) || empty($request->condition) || empty($request->library) || is_null($request->library)){
        //     return 1;
        // }


        $rules = new \App\Models\TblAutoresponses;
        $rules->service_id = $id;
        $rules->persona_id = $request->persona;
        $rules->delay = $request->delay;
        $rules->message_condition = $request->condition;
        $rules->keyword = $request->keyword;
        $rules->library_id = $request->library;
        $rules->date_created = gmdate('Y-m-d H:i:s');
        $rules->save();

        return 0;
    }

    public function edit_rule(Request $request, $id, $ruleId){
        $autoresp = \App\Models\TblAutoresponses::find($ruleId);
        $autoresp->service_id = $id;
        $autoresp->persona_id = $request->persona_id;
        $autoresp->delay = $request->delay;
        $autoresp->message_condition = $request->message_condition;
        if($request->message_condition === "first message"){
            $autoresp->keyword = "";
        }else{
            $autoresp->keyword = $request->keyword;
        }
        $autoresp->library_id = $request->library_id;
        $autoresp->save();

        return 0;
    }

    public function delete_rule(Request $request){
        $del = \App\Models\TblAutoresponses::where('id', $request->id)->delete();
        return $del;
    }

    //route
    public function get_service($filter){
        $query = \App\Models\TblServices::find($filter);
        return $query;
    }

    public function save_route(Request $request, $id){
        $routes = \App\Models\TblServices::find($id);
        if($request->sorting != "" and $request->sorting != NULL )
           $routes->route = $request->route;
        if($request->mapping != "" and $request->mapping != NULL )    
            $routes->mapping = $request->mapping;
        $routes->save();
        
        return 0;
    }

    public function save_message_limit(Request $request, $id){
        $message_limit = \App\Models\TblServices::find($id);
        $message_limit->message_limit = $request->limit;
        $message_limit->message_limit_action = $request->action;
        $message_limit->message_limit_reset_period = $request->reset;
        $message_limit->save();
        
        return 0;
    }

    public function save_persona_limit(Request $request, $id){
        $persona_limit = \App\Models\TblServices::find($id);
        $persona_limit->persona_limit = $request->limit;
        $persona_limit->persona_limit_action = $request->action;
        $persona_limit->persona_limit_reset_period = $request->reset;
        $persona_limit->save();

        return 0;
    }

    public function save_subscriber_billing(Request $request, $id){
        $billing = \App\Models\TblServices::find($id);
        $billing->subscriber_billing_idle_time = $request->idle;
        $billing->enable_subscriber_billing = $request->enable;
        $billing->save();

        return 0;
    }

    public function get_auto_discard($filter){
        $query = \App\Models\TblServices::find($filter)->tblAutoDiscard;
        return $query;
    }

    public function delete_auto_discard(Request $request){
        $del = \App\Models\TblAutoDiscard::where('id', $request->id)->delete();
        return $del;
    }
    public function save_auto_discard(Request $request, $id){
        $discard = new \App\Models\TblAutoDiscard;
        $discard->service_id = $id;
        $discard->start_time = $request->start_time;
        $discard->end_time = $request->end_time;
        $discard->save();

        return 0;
    }

}
