<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TblMessageInterval extends Model {

    public $timestamps = false;

    protected $table = 'tbl_message_interval';
    protected $fillable = ['user_id', 'inbound_id', 'outbound_id', 'inbound_time', 'assigned_time', 'outbound_time', 'response_interval', 'date_created'];


    public function TblMessages() {
        return $this->belongsTo(\App\Models\TblMessages::class, 'inbound_id', 'id');
    }

    public function rptMessageInterval() {
        return $this->hasMany(\App\Models\RptMessageInterval::class, 'message_interval_id', 'id');
    }

  public static function log_message_interval($inbound, $outbound, $user){

    $message_interval = \TblMessageInterval::where('inbound_id',$inbound->id)->orderBy('id','desc')->take(1)->get();

    if($message_interval->count() == 0){
      $interval = \DateTimeHelper::duration($inbound->bound_time, $outbound->bound_time, $user);
      $duration = \TblConversationDuration::where('conversation_id', $outbound->conversation_id)->orderBy('id','desc')->take(1)->get()->toArray();

      $msgInterval = new \TblMessageInterval();
      $msgInterval->user_id = $user->id;
      $msgInterval->inbound_id = $inbound->id;
      $msgInterval->outbound_id = $outbound->id;
      $msgInterval->inbound_time = $inbound->bound_time;
      $msgInterval->assigned_time = $duration[0]['assigned_time'];
      $msgInterval->outbound_time = $outbound->bound_time;
      $msgInterval->response_interval = $interval;
      $msgInterval->date_created = gmdate("Y-m-d H:i:s");
      $msgInterval->save();      

      $rptMsgInterval = new \RptMessageInterval();
      $rptMsgInterval->conversation_id = $inbound->conversation_id;
      $rptMsgInterval->inbound_id = $inbound->id;
      $rptMsgInterval->outbound_id = $outbound->id;
      $rptMsgInterval->user_id = $user->id;
      $rptMsgInterval->fullname = $user->full_name(2);
      $rptMsgInterval->service_id = $inbound->service_id;
      $rptMsgInterval->service_name = $inbound->service_name;
      $rptMsgInterval->inbound_time = $inbound->bound_time;
      $rptMsgInterval->assigned_time = $duration[0]['assigned_time'];
      $rptMsgInterval->outbound_time = $outbound->bound_time;
      $rptMsgInterval->response_interval = $interval;
      $rptMsgInterval->inbound_message = $inbound->message;
      $rptMsgInterval->outbound_message = $outbound->message;
      $rptMsgInterval->date_created = gmdate("Y-m-d H:i:s");
      $msgInterval->RptMessageInterval()->save($rptMsgInterval);
    }
  }
}
