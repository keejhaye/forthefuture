<?php

namespace App\Http\Controllers\Mock\Dev;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\TblGroups;

/**
 * Doc: https://docs.google.com/document/d/1WY5a6h4xjR0U9HYWa9LCsplrAHU8YdtEhQrMTTNmsow/edit?usp=sharing
 */
class Users extends Controller
{

    public function index(){
        echo 'Hello';
    }

    /**
     *Call to disconnect all users on IMv3
     */
    public function disconnect(){
        \Redis::publish('dev-actions', json_encode(['action' => 'disconnect']));
    }

    /**
     *Call to enable connection all users on IMv3
     */
    public function enableConnection(){
        \Redis::publish('dev-actions', json_encode(['action' => 'enable_connection']));
    }

    /**
     *Call to enable connection all users on IMv3
     */
    public function reloadpage(){
        \Redis::publish('dev-actions', json_encode(['action' => 'reload_page']));
    }

    public function remove_repeated_service(){
        $query = "SELECT user_id, service_id, COUNT(*) AS `count`
                    FROM tbl_user_service
                    GROUP BY user_id, service_id
                    HAVING COUNT(*) > 1";

        $result = \DB::select(\DB::raw($query));
        $deleted = 0;

        if(count($result) > 0){
            foreach ($result as $us){
                $limit = $us->count - 1;
                \DB::table('tbl_user_service')
                    ->where('user_id', $us->user_id)
                    ->where('service_id', $us->service_id)
                    ->limit($limit)->delete();

                $deleted += 1;
            }
        }

        echo "deleted: " . $deleted;
    }
}
