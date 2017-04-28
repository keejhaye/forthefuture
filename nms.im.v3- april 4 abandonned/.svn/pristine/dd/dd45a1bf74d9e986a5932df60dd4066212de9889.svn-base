<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TblSubscriberBilling extends Model {

    public $timestamps = false;

    protected $table = 'tbl_subscriber_billing';
    protected $fillable = ['subscriber_id', 'conversation_id', 'start_time', 'end_time', 'duration', 'status', 'date_created', 'user_id'];


    public function tblConversations() {
        return $this->belongsTo(\App\Models\TblConversations::class, 'conversation_id', 'id');
    }

    public function tblUsers() {
        return $this->belongsTo(\App\Models\TblUsers::class, 'user_id', 'id');
    }

    public function tblSubscribers() {
        return $this->belongsTo(\App\Models\TblSubscribers::class, 'subscriber_id', 'id');
    }

    public function rptSubscriberBilling() {
        return $this->hasMany(\App\Models\RptSubscriberBilling::class, 'subscriber_billing_id', 'id');
    }

    public function tblPendingSubscriberBilling() {
        return $this->hasMany(\App\Models\TblPendingSubscriberBilling::class, 'subscriber_billing_id', 'id');
    }

    static function end_subscriber_billing($conversation_id, $user, $end_time = null){
        $pending = \TblPendingSubscriberBilling::get_pending_billing(array("conversation_id" => $conversation_id));
        if($pending){
            if($end_time == null)
                $end_time = gmdate("Y-m-d H:i:s");

            $billing = $pending->TblSubscriberBilling;
            $duration = \DateTimeHelper::duration($billing->start_time, $end_time);
            $billing->end_time = $end_time;
            $billing->duration = $duration;
            $billing->status = "ended";
            $billing->user_id = $user->id;
            $billing->save();
            $rptSubscriberBilling = $billing->RptSubscriberBilling->first();
            $rptSubscriberBilling->user_id = $user->id;
            $rptSubscriberBilling->fullname = $user->full_name(2);
            $rptSubscriberBilling->end_time = $end_time;
            $rptSubscriberBilling->duration = $duration;
            $rptSubscriberBilling->status = "ended";
            $rptSubscriberBilling->save();

            //$timer = $pending->TblSubscriberBilling->TblConversationsMod->TblServices->subscriber_billing_idle_time;
            $pending->delete();
            //user::increment_chatting_time($billing->start_time, $billing->end_time, $timer);
        }
    } 
}
