<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TblMessages extends Model {

    public $timestamps = false;

    protected $table = 'tbl_messages';
    protected $fillable = ['user_id', 'conversation_id', 'status', 'message', 'bound_time', 'direction', 'assigned_time', 'duration', 'date_created', 'fetched', 'additional_info', 'conversation_duration_id', 'is_billed', 'ip_address'];


    public function TblConversations() {
        return $this->belongsTo('App\Models\TblConversations', 'conversation_id', 'id');
    }

    public function TblUser() {
        return $this->belongsTo('App\Models\TblUser', 'user_id', 'id');
    }

    public function TblServices() {
        return $this->belongsToMany('App\Models\TblService', 'tbl_aggregator_responses', 'message_id', 'service_id');
    }

    public function TblLibraryMessages() {
        return $this->belongsToMany('App\Models\TblLibraryMessage', 'message_id', 'library_message_id');
    }

    public function RptMessages() {
        return $this->hasMany('App\Models\RptMessages', 'message_id', 'id');
    }

    public function TblAggregatorResponses() {
        return $this->hasMany('App\Models\TblAggregatorResponses', 'message_id', 'id');
    }

    public function TblAutorespondedMessages() {
        return $this->hasMany('App\Models\TblAutorespondedMessage', 'message_id', 'id');
    }

    public function TblAutoresponseQueues() {
        return $this->hasMany('App\Models\TblAutoresponseQueue', 'message_id', 'id');
    }

    public function TblFlaggedMessages() {
        return $this->hasMany('App\Models\TblFlaggedMessages', 'message_id', 'id');
    }

    public function TblInboundMessageAttachment() {
        return $this->hasMany('App\Models\TblInboundMessageAttachment', 'message_id', 'id');
    }

    public function TblOutboundMessageAttachments() {
        return $this->hasMany('App\Models\TblOutboundMessageAttachments', 'message_id', 'id');
    }

    public static function get_relational_inbound_object($id){
        return \TblMessages::
                select('tbl_messages.*','tbl_services.name as service_name', 'tbl_personas.name as persona_name', 'tbl_subscribers.name as subscriber_name', 'tbl_services.aggregator_url','tbl_services.listener_username','tbl_services.listener_password','tbl_services.code','tbl_conversations.persona_id','tbl_conversations.subscriber_id','tbl_conversations.service_id')
                ->leftJoin('tbl_conversations','tbl_conversations.id','=','tbl_messages.conversation_id')
                ->leftJoin('tbl_services','tbl_services.id','=','tbl_conversations.service_id')
                ->leftJoin('tbl_personas','tbl_personas.id','=','tbl_conversations.persona_id')
                ->leftJoin('tbl_subscribers','tbl_subscribers.id','=','tbl_conversations.subscriber_id')
                ->where('tbl_messages.id','=',$id)
                // ->toSql();
                ->first();
    }
}
