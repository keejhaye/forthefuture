<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TblConversations extends Model {

    public $timestamps = false;

    protected $table = 'tbl_conversations';
    protected $fillable = ['persona_id', 'subscriber_id', 'service_id', 'status', 'date_created'];


    public function tblPersonas() {
        return $this->belongsTo(\App\Models\TblPersonas::class, 'persona_id', 'id');
    }

    public function tblServices() {
        return $this->belongsTo(\App\Models\TblServices::class, 'service_id', 'id');
    }

    public function tblSubscribers() {
        return $this->belongsTo(\App\Models\TblSubscribers::class, 'subscriber_id', 'id');
    }

//    public function tblUsers() {
//        return $this->belongsToMany(\App\Models\TblUsers::class, 'tbl_conversation_logs', 'conversation_id', 'user_id');
//    }
//
//    public function tblUsers() {
//        return $this->belongsToMany(\App\Models\TblUsers::class, 'tbl_conversation_messages_logs', 'conversation_id', 'user_id');
//    }
//
//    public function tblUsers() {
//        return $this->belongsToMany(\App\Models\TblUsers::class, 'tbl_discarded_conversations', 'conversation_id', 'user_id');
//    }
//
//    public function tblUsers() {
//        return $this->belongsToMany(\App\Models\TblUsers::class, 'tbl_timed_out_conversations', 'conversation_id', 'user_id');
//    }

    public function tblConversationComments() {
        return $this->hasMany(\App\Models\TblConversationComments::class, 'conversation_id', 'id');
    }

    public function tblConversationDurations() {
        return $this->hasMany(\App\Models\TblConversationDuration::class, 'conversation_id', 'id');
    }

    public function tblConversationLogs() {
        return $this->hasMany(\App\Models\TblConversationLogs::class, 'conversation_id', 'id');
    }

    public function tblConversationMessagesLogs() {
        return $this->hasMany(\App\Models\TblConversationMessagesLogs::class, 'conversation_id', 'id');
    }

    public function tblDiscardedConversations() {
        return $this->hasMany(\App\Models\TblDiscardedConversations::class, 'conversation_id', 'id');
    }

    public function tblIgnoredConversationLogs() {
        return $this->hasMany(\App\Models\TblIgnoredConversationLogs::class, 'conversation_id', 'id');
    }

    public function tblMessages() {
        return $this->hasMany(\App\Models\TblMessages::class, 'conversation_id', 'id');
    }

    public function tblPendingConversations() {
        return $this->hasMany(\App\Models\TblPendingConversations::class, 'conversation_id', 'id');
    }

    public function tblSubscriberBilling() {
        return $this->hasMany(\App\Models\TblSubscriberBilling::class, 'conversation_id', 'id');
    }

    public function tblTimedOutConversations() {
        return $this->hasMany(\App\Models\TblTimedOutConversations::class, 'conversation_id', 'id');
    }

    public static function fetch_conversation($subscriber_id, $persona_id, $service_id, $conversation, $status = 'pending'){

        $result["is_new"] = false;
        $conversation = self::get_relational_conversation_object($subscriber_id, $persona_id);
        if (empty($conversation)) {
            $conversation = new \TblConversations();
            $conversation->persona_id = $persona_id;
            $conversation->subscriber_id = $subscriber_id;
            $conversation->service_id = $service_id;
            $conversation->status = $status;
            $conversation->date_created = gmdate(\Config::get('application.date_format_php'));
            $conversation->save();
            $meta = self::get_conversation_meta($conversation->id);
            $conversation->persona_name = $meta->persona_name;
            $conversation->subscriber_name = $meta->subscriber_name;
            $conversation->service_name = $meta->service_name;
            
            $result["is_new"] = true;
            $log = array("time"=> gmdate("Y-m-d H:i:s"), "message" => "A new conversation has been created");
            \LogHelper::log_array_to_session("inbound-process", $log);
        }
        $result["conversation"] = $conversation;
        return $result;
    }

    public static function get_conversation($subscriber_id, $persona_id) {
        return \DB::table('tbl_conversations')
                    ->where("subscriber_id",'=', $subscriber_id)
                    ->where("persona_id",'=', $persona_id)
                    ->first();
    }


    public static function get_relational_conversation_object($subscriber_id, $persona_id){
        return \TblConversations::select(
                    'tbl_conversations.id',
                    'tbl_conversations.status',
                    'tbl_services.name as service_name', 
                    'tbl_personas.name as persona_name', 
                    'tbl_subscribers.name as subscriber_name', 
                    'tbl_services.aggregator_url',
                    'tbl_services.listener_username',
                    'tbl_services.listener_password',
                    'tbl_services.code',
                    'tbl_conversations.persona_id',
                    'tbl_conversations.subscriber_id',
                    'tbl_conversations.service_id',
                    'tbl_services.enable_subscriber_billing' 
                )
                ->leftJoin('tbl_services','tbl_services.id','=','tbl_conversations.service_id')
                ->leftJoin('tbl_personas','tbl_personas.id','=','tbl_conversations.persona_id')
                ->leftJoin('tbl_subscribers','tbl_subscribers.id','=','tbl_conversations.subscriber_id')
                ->where("tbl_conversations.subscriber_id",'=', $subscriber_id)
                ->where("tbl_conversations.persona_id",'=', $persona_id)
                ->first();
    }

    public static function get_conversation_meta($id) {
        return \TblConversations::select(
                    'tbl_services.name as service_name', 
                    'tbl_personas.name as persona_name', 
                    'tbl_subscribers.name as subscriber_name',
                    'tbl_services.enable_subscriber_billing'
                )
                ->leftJoin('tbl_services','tbl_services.id','=','tbl_conversations.service_id')
                ->leftJoin('tbl_personas','tbl_personas.id','=','tbl_conversations.persona_id')
                ->leftJoin('tbl_subscribers','tbl_subscribers.id','=','tbl_conversations.subscriber_id')
                ->where("tbl_conversations.id",'=',$id)
                ->first();
    }

    public static function has_no_message($id){
        $sql = "SELECT id FROM tbl_messages WHERE conversation_id = {$id} LIMIT 1";
        $result = \DB::select($sql);
        return (count($result) > 0 ? false : true);
    }
}
