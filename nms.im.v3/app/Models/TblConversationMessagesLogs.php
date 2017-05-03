<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TblConversationMessagesLogs extends Model {

    public $timestamps = false;

    protected $table = 'tbl_conversation_messages_logs';
    protected $fillable = ['conversation_id', 'conversation_duration_id', 'message_id', 'user_id', 'comments', 'date_created', 'url'];


    public function tblConversations() {
        return $this->belongsTo(\App\Models\TblConversations::class, 'conversation_id', 'id');
    }

    public function tblUsers() {
        return $this->belongsTo(\App\Models\TblUsers::class, 'user_id', 'id');
    }


}
