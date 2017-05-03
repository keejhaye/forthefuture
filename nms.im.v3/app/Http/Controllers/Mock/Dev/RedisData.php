<?php

namespace App\Http\Controllers\Mock\Dev;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\TblGroups;

/**
 * Doc: https://docs.google.com/document/d/1WY5a6h4xjR0U9HYWa9LCsplrAHU8YdtEhQrMTTNmsow/edit?usp=sharing
 */
class RedisData extends Controller
{

    public function index(){
        echo 'Hello';
    }

    /**
     * Creates hashes of role_names on Redis
     */
    public function setRoleNames(){
        $roles = \TblRoles::all(['id', 'name'])->toArray();
        $key = 'role_names';
        $role_id_name = [];

        foreach ($roles as $role) {
            $role_id_name[$role['id']] = $role['name'];
        }
        
        \Redis::hmset($key, $role_id_name);

        echo '<h2>Success</h2>';
        $this->getRoleNames();
    }

    /**
     * Displays an array of role_ids and role_names retrieved from Redis
     */
    public function getRoleNames(){
        // $data = \Redis::hget('role_names', 2);
        $data = \Redis::hgetall('role_names');
        var_dump($data);
    }

    public function setPermissions($rolename = "all"){
        if($rolename == 'all'){
            $this->setPermissionsForAll();
        }else{
            $role = \TblRoles::where('name', $rolename)->first(['id' , 'permissions'])->toArray();
            if(!empty($role)){
                $list = array_keys(json_decode($role['permissions'], true));
                if($list){
                    $res = \Redis::sadd('role_permissions:'.$role['id'], $list);
                    echo 'Success';
                    echo '<br>';
                    echo 'Role: '.$rolename.' | '.$res.' permissions added';
                }else{
                    echo 'No permissions for '.$rolename;
                }
            }else{
                echo 'Role not found';
            }
        }
    }

    /**
     * Creates a redis set for all role
     */
    public function setPermissionsForAll(){
        $roles = \TblRoles::all(['id','name','permissions'])->toArray();
        $notif = '';

        if($roles){
            $notif .= 'Success<br>';
            foreach ($roles as $role) {
                $list = array_keys(json_decode($role['permissions'], true));

                if(!empty($list)){
                    $res = \Redis::sadd('role_permissions:'.$role['id'], $list);
                    $notif .= 'Role: '.$role['id'].'-'.$role['name'].' | '.$res.' permissions added<br>';
                }else{
                    $notif .= 'Role: '.$role['id'].'-'.$role['name'].' | no permissions found<br>';
                }
            }
        }else{
            $notif .= 'No data found';
        }
        

        echo $notif;
    }

    public function getPermissions($rolename = "all"){
        $query = \TblRoles::where('name', '!=', 'loop');

        if($rolename != 'all'){
            $query->where('name', $rolename);
        }
        $role_ids = $query->get(['id' , 'name'])->toArray();

        if($role_ids){
            foreach ($role_ids as $key => $val) {
                echo '<pre>';
                echo "<h3>".$val['id']." ".$val['name']."</h3>";
                print_r(\Redis::smembers('role_permissions:'.$val['id']));
                echo '-----------------------------------------------';
                echo '</pre>';
            }
        }else{
            echo 'Role not found';
        }
    }

    public function resetStatistics(){
        $assigned_conversations = \Redis::keys('assigned_conversations_*');
        $unassigned_conversations = \Redis::keys('unassigned_conversations_*');
        $total_inbound = \Redis::keys('total_inbound_*');
        $total_outbound = \Redis::keys('total_outbound_*');

        foreach($assigned_conversations as $key){
            \Redis::set($key, 0);
        }
        foreach($unassigned_conversations as $key){
            \Redis::set($key, 0);
        }
        foreach($total_inbound as $key){
            \Redis::set($key, 0);
        }
        foreach($total_outbound as $key){
            \Redis::set($key, 0);
        }

        \Redis::set('assigned_conversations', 0);
        \Redis::set('unassigned_conversations', 0);
        \Redis::set('online_count', 0);
        \Redis::set('total_inbound', 0);
        \Redis::set('total_outbound', 0);
        \Redis::set('chatting_count', 0);

        echo session()->token();
    }

    public function updateStatistics(){
        $inbound = \TblMessages::where('direction', 'inbound')
                        ->where('bound_time', '>', '2017-01-18 02:00:00')
                        ->get();

        $outbound = \TblMessages::where('direction', 'outbound')
                        ->where('bound_time', '>', '2017-01-18 02:00:00')
                        ->get();

        $unassigned = \TblConversations::where('status', 'pending')
                        ->where('assigned_latest', '>', '2017-01-18 02:00:00')
                        ->get();

        $assigned = \TblConversations::where('status', 'assigned')
                        ->where('assigned_latest', '>', '2017-01-18 02:00:00')
                        ->get();

        $online = \TblLoggedInUsers::count();

        $chatting = \TblLoggedInUsers::where('status', 'chat')
                        ->get();

        \Redis::set('assigned_conversations', $assigned->count());
        \Redis::set('unassigned_conversations', $unassigned->count());
        \Redis::set('online_count', $online);
        \Redis::set('total_inbound', $inbound->count());
        \Redis::set('total_outbound', $outbound->count());
         \Redis::set('chatting_count', $chatting->count());
    }

    public function deleteServicesStatistics(){
        $online = \Redis::keys('online_count_*');
        $inbound = \Redis::keys('total_inbound_*');
        $outbound = \Redis::keys('total_outbound_*');
        $assigned = \Redis::keys('assigned_conversations_*');
        $unassigned = \Redis::keys('unassigned_conversations_*');
         $chatting = \Redis::keys('chatting_count_*');

        $keys = array_merge($online, $inbound, $outbound, $assigned, $unassigned, $chatting);
        // echo "<pre>" . print_r($keys,1) . "</pre>";

        foreach($keys as $key){
            \Redis::del($key);
        }
    }

    public function currentServicesStatistics(){
        $date = [
          'start' => \DateTimeHelper::get_date('Y-m-d H:i:s', gmdate('Y-m-d 00:00:00'), '11'),
          'end' => \DateTimeHelper::get_date('Y-m-d H:i:s', gmdate('Y-m-d 23:59:59'), '11')
        ];

        $query = "SELECT s.id,
                    (SELECT COUNT(m.id) FROM tbl_messages m 
                        LEFT JOIN tbl_conversations c ON c.id = m.id
                        WHERE m.direction = 'inbound'
                        AND m.bound_time BETWEEN '{$date['start']}' AND '{$date['end']}'
                        AND c.service_id = s.id) AS inbound,
                    (SELECT COUNT(m.id) FROM tbl_messages m 
                        LEFT JOIN tbl_conversations c ON c.id = m.id
                        WHERE m.direction = 'outbound'
                        AND m.bound_time BETWEEN '{$date['start']}' AND '{$date['end']}'
                        AND c.service_id = s.id) AS outbound,
                    (SELECT COUNT(c.id) FROM tbl_conversations c
                        WHERE c.status = 'assigned' AND c.service_id = s.id) AS assigned,
                    (SELECT COUNT(c.id) FROM tbl_conversations c
                        WHERE c.status = 'pending' AND c.service_id = s.id) AS unassigned,
                    (SELECT COUNT(DISTINCT l.id) FROM tbl_logged_in_users l
                        LEFT JOIN tbl_user_service us ON us.user_id = l.user_id
                        WHERE us.service_id = s.id) AS online,
                    (SELECT COUNT(DISTINCT l1.id) FROM tbl_logged_in_users l1
                        LEFT JOIN tbl_user_service us ON us.user_id = l1.user_id
                        WHERE us.service_id = s.id) AS chatting
                FROM tbl_services s ORDER BY s.id";

        $result = \DB::select(\DB::raw($query));
        
        foreach ($result as $row) {
            \Redis::set("total_inbound:".$row->id, $row->inbound);
            \Redis::set("total_outbound:".$row->id, $row->outbound);
            \Redis::set("assigned_conversations:".$row->id, $row->assigned);
            \Redis::set("unassigned_conversations:".$row->id, $row->unassigned);
            \Redis::set("online_count:".$row->id, $row->online);
             \Redis::set("online_count:".$row->id, $row->chatting);
        }

        echo "<pre>" . print_r($result,1) . "</pre>";
    }

    public function current(){
        $date = [
          'start' => \DateTimeHelper::get_date('Y-m-d H:i:s', gmdate('Y-m-d 00:00:00'), '11'),
          'end' => \DateTimeHelper::get_date('Y-m-d H:i:s', gmdate('Y-m-d 23:59:59'), '11')
        ];

        $total = "SELECT 
                  (SELECT COUNT(id) FROM rpt_messages WHERE bound_time BETWEEN '{$date['start']}' AND '{$date['end']}' AND direction = 'inbound') AS inbound,
                  (SELECT COUNT(id) FROM rpt_messages WHERE bound_time BETWEEN '{$date['start']}' AND '{$date['end']}' AND direction = 'outbound') AS outbound";

        $result = \DB::select(\DB::raw($total));

        \Redis::set('total_inbound', $result[0]->inbound);
        \Redis::set('total_outbound', $result[0]->outbound);
        die(print_r($result,1));
    }

    public function set_online_user_services(){
        //get services of online users and set it on redis
        $user_services_query = "SELECT l.`user_id`, us.`service_id` FROM tbl_logged_in_users l 
                    LEFT JOIN tbl_user_service us ON l.`user_id` = us.`user_id` 
                    ORDER BY l.`user_id`";

        $result = \DB::select(\DB::raw($user_services_query));
        
        $user_services = [];
        foreach ($result as $row){
            if(!isset($user_services[$row->user_id])){
                $user_services[$row->user_id] = [];
                $user_services[$row->user_id]['user_id'] = $row->user_id;
                $user_services[$row->user_id]['services'] = [];
            }

            array_push($user_services[$row->user_id]['services'],($row->service_id));
        }

        foreach ($user_services as $us) {
            \Redis::set('user_services:'.$us['user_id'], json_encode($us['services']));
        }
        
        die("<pre>".print_r($user_services,1));
    }

}
