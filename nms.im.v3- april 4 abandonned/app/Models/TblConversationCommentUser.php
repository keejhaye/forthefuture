<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TblConversationCommentUser extends Model {

    public $timestamps = false;

    protected $table = 'tbl_conversation_comment_users';
    protected $fillable = ['conversation_comment_id', 'user_id', 'is_read', 'date_read'];


    public function tblUsers() {
        return $this->belongsTo(\App\Models\TblUsers::class, 'user_id', 'id');
    }

    public function tblConversationComments() {
        return $this->belongsTo(\App\Models\TblConversationComments::class, 'conversation_comment_id', 'id');
    }


}
