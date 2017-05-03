<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RptUserConversationLogs extends Model {

    public $timestamps = false;

    protected $table = 'rpt_user_conversation_logs';
    protected $fillable = ['user_conversation_logs_id', 'user_id', 'fullname', 'start_time', 'end_time', 'status', 'duration', 'date_created'];

}
