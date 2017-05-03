<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TblUserConversationLogs extends Model {

    public $timestamps = false;

    protected $table = 'tbl_user_conversation_logs';
    protected $fillable = ['user_id', 'start_time', 'end_time', 'status', 'duration', 'date_created'];


    public function tblUsers() {
        return $this->belongsTo(\App\Models\TblUsers::class, 'user_id', 'id');
    }

    public function rptUserConversationLogs() {
        return $this->hasMany(\App\Models\RptUserConversationLogs::class, 'user_conversation_logs_id', 'id');
    }


}
