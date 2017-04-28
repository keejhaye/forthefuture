<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TblConversationLogs extends Model {

    public $timestamps = false;

    protected $table = 'tbl_conversation_logs';
    protected $fillable = ['conversation_id', 'user_id', 'comment', 'date_created', 'url'];


    public function tblConversations() {
        return $this->belongsTo(\App\Models\TblConversations::class, 'conversation_id', 'id');
    }

    public function tblUsers() {
        return $this->belongsTo(\App\Models\TblUsers::class, 'user_id', 'id');
    }


}
