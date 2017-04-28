<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TblFlaggedMessages extends Model {

    public $timestamps = false;

    protected $table = 'tbl_flagged_messages';
    protected $fillable = ['message_id', 'operator_id', 'user_id', 'status', 'comments', 'date_flagged', 'date_moderated'];


    public function tblMessages() {
        return $this->belongsTo(\App\Models\TblMessages::class, 'message_id', 'id');
    }

    public function tblUsers_by_operator_id() {
        return $this->belongsTo(\App\Models\TblUsers::class, 'operator_id', 'id');
    }

    public function tblUsers() {
        return $this->belongsTo(\App\Models\TblUsers::class, 'user_id', 'id');
    }

    public function tblFlaggedMessageDeductions() {
        return $this->hasMany(\App\Models\TblFlaggedMessageDeductions::class, 'flagged_message_id', 'id');
    }

    public function tblFlaggedMessagesStatistic() {
        return $this->hasMany(\App\Models\TblFlaggedMessagesStatistic::class, 'operator_id', 'operator_id');
    }
    
    public static function search($params){
        $q = \TblFlaggedMessages::
                select('tbl_flagged_messages.*');
        
        if(isset($params["service_id"])){
            $q->leftJoin('tbl_messages', 'tbl_flagged_messages.message_id', '=', 'tbl_messages.id')
               ->leftJoin('tbl_conversations', 'tbl_messages.conversation_id','=','tbl_conversations.id')
               ->where('tbl_conversations.service_id', '=', $params['service_id']);       
        }
                
        return $q->get();
    }
    


}
