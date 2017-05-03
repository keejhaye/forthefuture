<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TblMessagesQueue extends Model {

    public $timestamps = false;

    protected $table = 'tbl_messages_queue';
    protected $fillable = ['details', 'status', 'inbound_time', 'execution', 'executed', 'execution_info', 'date_created', 'remarks', 'is_failed', 'attempt', 'service_id'];

	public static function get_pending_queue($params = null){

		$pending_queue = \TblMessagesQueue::where('status','=','pending');

		if(isset($params["in_service"]))
		  $pending_queue->whereIn("service_id", $params["in_service"]);

		return $pending_queue->take(1)->get();

	}

}
