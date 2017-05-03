<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TblIgnoredConversationLogs extends Model {

    public $timestamps = false;

    protected $table = 'tbl_ignored_conversation_logs';
    protected $fillable = ['user_id', 'conversation_id', 'conversation_duration_id', 'date_created'];


    public function tblConversations() {
        return $this->belongsTo(\App\Models\TblConversations::class, 'conversation_id', 'id');
    }

    public function tblConversationDuration() {
        return $this->belongsTo(\App\Models\TblConversationDuration::class, 'conversation_duration_id', 'id');
    }

    public function tblUsers() {
        return $this->belongsTo(\App\Models\TblUsers::class, 'user_id', 'id');
    }


}
