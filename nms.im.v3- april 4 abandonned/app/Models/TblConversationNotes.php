<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TblConversationNotes extends Model {

    public $timestamps = false;

    protected $table = 'tbl_conversation_notes';
    protected $fillable = ['user_id', 'conversation_id', 'comment', 'date_created', 'type'];


    public function tblConversations() {
        return $this->belongsTo(\App\Models\TblConversations::class, 'conversation_id', 'id');
    }

    public function tblUsers() {
        return $this->belongsTo(\App\Models\TblUsers::class, 'user_id', 'id');
    }


}
