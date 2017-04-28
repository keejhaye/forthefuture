<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TblUserLogs extends Model {

    public $timestamps = false;

    protected $table = 'tbl_user_logs';
    protected $fillable = ['user_id', 'status', 'start_time', 'end_time', 'date_created', 'active'];


    public function tblUsers() {
        return $this->belongsTo(\App\Models\TblUsers::class, 'user_id', 'id');
    }

    public function rptUserLogs() {
        return $this->hasMany(\App\Models\RptUserLogs::class, 'user_log_id', 'id');
    }

    public function get_active_user_log($userid, $status = null){
    	$q = \TblUserLogs::where('user_id', '=', $userid)
    			->where('active', '=', 1)
    			->orderBy('start_time', 'desc');

	    if($status != null)
	    	$q->where('status', '!=', $status);

	   	return $q->first();
	}

	public static function add_log($id, $status){
	    $user = \TblUsers::find($id);
	    $model = new \TblUserLogs();
	    $model->user_id = $id;
	    $model->status = $status;
	    $model->start_time = date("Y-m-d H:i:s");
	    $model->date_created = date("Y-m-d H:i:s");

	    $rptuserlogs = new \RptUserLogs([
	    	'user_id' => $id,
	    	'fullname' => $user->full_name(2),
	    	'status' => $status,
	    	'start_time' => date("Y-m-d H:i:s"),
	    	'date_created' => date("Y-m-d H:i:s"),
	   	]);

	    $model->save();
	    $model->RptUserLogs()->save($rptuserlogs);
	}
}
