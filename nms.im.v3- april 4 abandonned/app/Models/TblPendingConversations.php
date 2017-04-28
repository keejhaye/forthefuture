<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TblPendingConversations extends Model {

    public $timestamps = false;

    protected $table = 'tbl_pending_conversations';
    protected $fillable = ['conversation_id', 'user_id', 'status', 'assigned_time', 'last_message', 'conversation_duration_id', 'time_fetched'];


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
