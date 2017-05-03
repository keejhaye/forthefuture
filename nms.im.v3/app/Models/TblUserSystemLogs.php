<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TblUserSystemLogs extends Model {

    public $timestamps = false;

    protected $table = 'tbl_user_system_logs';
    protected $fillable = ['user_id', 'fullname', 'status', 'start_time', 'end_time', 'date_created', 'ip_address', 'active', 'remarks'];


    public function rptUserSystemLogs() {
        return $this->hasMany(\App\Models\RptUserSystemLogs::class, 'user_log_id', 'id');
    }

    public function get_active_user_log($userid, $status = null){
    	$q = \TblUserSystemLogs::where('user_id', '=', $userid)
    			->where('active', '=', 1)
    			->orderBy('start_time', 'desc');
				
	    if($status != null)
	    	$q->where('status', '!=', $status);
	   
	    return $q->first();
	}

	public static function add_log($id, $status, $remarks){
	    $user = \TblUsers::where('id', '=', $id)->first();
	    $model = new \TblUserSystemLogs();
	    $model->user_id = $id;
	    $model->fullname = $user->firstname." ".$user->lastname;
	    $model->status = $status;
	    $model->start_time = date("Y-m-d H:i:s");
	    $model->date_created = date("Y-m-d H:i:s");
	    $model->ip_address = $_SERVER['REMOTE_ADDR'];
	    $model->remarks = $remarks;

	    $systemlogs = new \RptUserSystemLogs();
	    $systemlogs->user_id = $id;
	    $systemlogs->fullname = $user->firstname." ".$user->lastname;
	    $systemlogs->status = $status;
	    $systemlogs->start_time = date("Y-m-d H:i:s");
	    $systemlogs->date_created = date("Y-m-d H:i:s");
	    $systemlogs->remarks = $remarks;
	    $systemlogs->ip_address = $_SERVER['REMOTE_ADDR'];

	    $model->save();
	    $model->RptUserSystemLogs()->save($systemlogs);
	}

}
