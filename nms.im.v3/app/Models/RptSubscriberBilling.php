<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RptSubscriberBilling extends Model {

    public $timestamps = false;

    protected $table = 'rpt_subscriber_billing';
    protected $fillable = ['subscriber_billing_id', 'subscriber_id', 'subscriber_name', 'service_id', 'service_name', 'conversation_id', 'start_time', 'end_time', 'duration', 'status', 'date_created', 'user_id', 'fullname'];


    public function tblSubscriberBilling() {
        return $this->belongsTo(\App\Models\TblSubscriberBilling::class, 'subscriber_billing_id', 'id');
    }


}
