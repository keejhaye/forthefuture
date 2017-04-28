<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TblPendingSubscriberBilling extends Model {

    public $timestamps = false;

    protected $table = 'tbl_pending_subscriber_billing';
    protected $fillable = ['subscriber_billing_id', 'status', 'date_created'];


    public function TblSubscriberBilling() {
        return $this->belongsTo(\App\Models\TblSubscriberBilling::class, 'subscriber_billing_id', 'id');
    }

	public static function get_pending_billing($params){
		$query = \TblPendingSubscriberBilling::select('tbl_pending_subscriber_billing.*')
												->leftJoin("tbl_subscriber_billing",'tbl_subscriber_billing.id','=','tbl_pending_subscriber_billing.subscriber_billing_id')
												->where("tbl_pending_subscriber_billing.status","=","ongoing");

		if(isset($params["conversation_id"]))
		$query->where("tbl_subscriber_billing.conversation_id","=",$params["conversation_id"]);

		return $query->first();
	}

}
