<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class TblLoggedInUsers extends Model {

    public $timestamps = false;

    protected $table = 'tbl_logged_in_users';
    protected $fillable = ['user_id', 'status', 'time_parked', 'chat_time', 'last_ping', 'url', 'last_outbound', 'assigned', 'is_selected'];


    public function tblUsers() {
        return $this->belongsTo(\App\Models\TblUsers::class, 'user_id', 'id');
    }

    public static function fetch_online_users(){
     $query = \TblLoggedInUsers::
                select('tbl_logged_in_users.*','tbl_users.*', 'tbl_logged_in_users.status as status', 'tbl_roles.name')
                ->leftJoin('tbl_users', 'tbl_users.id', '=', 'tbl_logged_in_users.user_id')
                ->leftJoin('tbl_roles', 'tbl_roles.id', '=', 'tbl_users.role_id')
                ->orderBy('tbl_users.id', 'desc')
                ->get();
                return $query;
    }
}
