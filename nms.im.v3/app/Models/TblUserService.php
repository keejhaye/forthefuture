<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class TblUserService extends Model {

    public $timestamps = false;

    protected $table = 'tbl_user_service';
    protected $fillable = ['user_id', 'service_id', 'date_created'];


    public function tblUsers() {
        return $this->belongsTo(\App\Models\TblUsers::class, 'user_id', 'id');
    }

    public function tblServices() {
        return $this->belongsTo(\App\Models\TblServices::class, 'service_id', 'id');
    }

    public static function get_user_services($user_id){
    	$query = TblUserService::where('user_id', $user_id)->get()->toArray();
	    $services = array();

	    if(!empty($query)){
	      foreach ($query as $svc){
	        $services[] = $svc["service_id"];
	      }
	    }
	    
	    return $services;
	}
    public static function search($params){
        $query = \DB::table('tbl_user_service AS us')
                  ->leftJoin('tbl_users AS u', 'u.id', '=', 'us.user_id')
                  ->leftJoin('tbl_roles AS g', 'g.id', '=', 'u.role_id')
                  ;

        if(isset($params['service_id']) && !empty($params['service_id']))
            $query->where('us.service_id', $params['service_id']);
        
        return $query;
    }

    public static function getUserNameById($id){
        $users = DB::table('tbl_user_service')
            ->join('tbl_users', 'tbl_user_service.user_id', '=', 'tbl_users.id')
            ->select('tbl_user_service.*', 'tbl_users.username')
            ->where('tbl_user_service.service_id', $id)
            ->get();
        return $users;
    }


    public static function getServiceNameById($id){
        $users = DB::table('tbl_user_service')
            ->join('tbl_services', 'tbl_user_service.service_id', '=', 'tbl_services.id')
            ->select('tbl_user_service.*', 'tbl_services.name', 'tbl_services.code')
            ->where('tbl_user_service.user_id', $id)
            ->get();
        return $users;
    }


}
