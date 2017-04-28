<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TblDiscardedConversations extends Model {

    public $timestamps = false;

    protected $table = 'tbl_discarded_conversations';
    protected $fillable = ['conversation_id', 'user_id', 'date_created'];


    public function tblUsers() {
        return $this->belongsTo(\App\Models\TblUsers::class, 'user_id', 'id');
    }

    public function tblConversations() {
        return $this->belongsTo(\App\Models\TblConversations::class, 'conversation_id', 'id');
    }


}
