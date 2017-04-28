<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TblConversationComments extends Model {

    public $timestamps = false;

    protected $table = 'tbl_conversation_comments';
    protected $fillable = ['user_id', 'conversation_id', 'message_id', 'comment', 'date_created', 'date_updated'];


    public function tblUsers() {
        return $this->belongsTo(\App\Models\TblUsers::class, 'user_id', 'id');
    }

    public function tblConversations() {
        return $this->belongsTo(\App\Models\TblConversations::class, 'conversation_id', 'id');
    }

    public function tblMessages() {
        return $this->belongsTo(\App\Models\TblMessages::class, 'message_id', 'id');
    }

    // public function tblUsers() {
    //     return $this->belongsToMany(\App\Models\TblUsers::class, 'tbl_conversation_comment_users', 'conversation_comment_id', 'user_id');
    // }

    public function tblConversationCommentUser() {
        return $this->hasMany(\App\Models\TblConversationCommentUser::class, 'conversation_comment_id', 'id');
    }


}
