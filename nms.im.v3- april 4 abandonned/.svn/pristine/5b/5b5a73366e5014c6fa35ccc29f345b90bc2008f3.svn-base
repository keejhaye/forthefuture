<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TblConversationDuration extends Model {

    public $timestamps = false;
    protected $table = 'tbl_conversation_duration';
    protected $fillable = ['conversation_id', 'user_id', 'assigned_time', 'time_started', 'time_ended', 'duration', 'status', 'time_fetched', 'fetch_count', 'last_inbound_message', 'last_inbound_time', 'last_outbound_message', 'last_outbound_time', 'first_message_id', 'last_message_id'];

    public function tblUsers() {
        return $this->belongsTo(\App\Models\TblUsers::class, 'user_id', 'id');
    }

    public function tblConversations() {
        return $this->belongsTo(\App\Models\TblConversations::class, 'conversation_id', 'id');
    }

    public function rptConversationDuration() {
        return $this->hasMany(\App\Models\RptConversationDuration::class, 'conversation_duration_id', 'id');
    }

    public function tblIgnoredConversationLogs() {
        return $this->hasMany(\App\Models\TblIgnoredConversationLogs::class, 'conversation_duration_id', 'id');
    }

    public function tblMessages() {
        return $this->hasMany(\App\Models\TblMessages::class, 'conversation_duration_id', 'id');
    }

    public function tblPendingConversations() {
        return $this->hasMany(\App\Models\TblPendingConversations::class, 'conversation_duration_id', 'id');
    }

    public static function update_outbound_conversations($data) {
        $cd = \DB::table('tbl_conversation_duration')
                ->where('conversation_id', '=', $data['conversation_id'])
                ->where('status', '=', 'ongoing-in')
                ->orderBy('id', 'DESC')
                ->take(1)
                ->update([
                    'last_outbound_message' => $data['last_outbound_message'],
                    'last_outbound_time' => $data['last_outbound_time'],
                    'last_message_id' => $data['last_message_id'],
                    'status' => 'ongoing-out'
                    ]);

        \DB::table('rpt_conversation_duration')
                ->where('conversation_id', '=', $data['conversation_id'])
                ->orderBy('id', 'DESC')
                ->take(1)
                ->update([
                    'last_outbound_message' => $data['last_outbound_message'],
                    'last_outbound_time' => $data['last_outbound_time'],
                    'last_message_id' => $data['last_message_id'],
                    'status' => 'ongoing-out'
        ]);
    }

    public static function fetch() {
       return \TblConversationDuration::
                select('tbl_conversation_duration.*','tbl_services.name', 'tbl_personas.name as persona', 'tbl_subscribers.name as subscriber', 'tbl_users.username as operator')
                ->leftJoin('tbl_conversations', 'tbl_conversation_duration.conversation_id', '=', 'tbl_conversations.id')
                ->leftJoin('tbl_messages', 'tbl_messages.id', '=', 'tbl_conversation_duration.last_message_id')
                ->leftJoin('tbl_services', 'tbl_services.id', '=', 'tbl_conversations.service_id')
                ->leftJoin('tbl_personas', 'tbl_personas.id', '=', 'tbl_conversations.persona_id')
                ->leftJoin('tbl_subscribers', 'tbl_subscribers.id', '=', 'tbl_conversations.subscriber_id')
                ->leftJoin('tbl_users', 'tbl_users.id', '=', 'tbl_conversation_duration.user_id')
                ->orderBy('tbl_conversation_duration.id', 'desc')
                ->get();
    }

    public static function search($params){
        $query = \TblConversationDuration::
                select('tbl_conversation_duration.*'
                    ,'tbl_services.name'
                    ,'tbl_personas.name as persona' 
                    ,'tbl_subscribers.name as subscriber' 
                    ,'tbl_users.username as operator')
                ->leftJoin('tbl_conversations', 'tbl_conversation_duration.conversation_id', '=', 'tbl_conversations.id')
                ->leftJoin('tbl_messages', 'tbl_messages.id', '=', 'tbl_conversation_duration.last_message_id')
                ->leftJoin('tbl_services', 'tbl_services.id', '=', 'tbl_conversations.service_id')
                ->leftJoin('tbl_personas', 'tbl_personas.id', '=', 'tbl_conversations.persona_id')
                ->leftJoin('tbl_subscribers', 'tbl_subscribers.id', '=', 'tbl_conversations.subscriber_id')
                ->leftJoin('tbl_users', 'tbl_users.id', '=', 'tbl_conversation_duration.user_id')
                ->orderBy('tbl_conversation_duration.id', 'desc');

                if(\Redis::sismember('group_permissions:'.session('user.group_id'), 'own_services_only')){
            $query->whereIn("tbl_conversations.service_id", session('user.services'));
        }

        if(isset($params['service_id']) && !empty($params['service_id']))
            $query->where("tbl_conversations.service_id", $params['service_id']);

        if(isset($params['user_id']) && !empty($params['user_id']))
            $query->where("tbl_users.id", $params['user_id']);

        if(isset($params['status']) && !empty($params['status']))
            $query->where("tbl_conversation_duration.status", $params['status']);

        if(isset($params['start_date']) && isset($params['end_date']))
            $query->whereBetween("tbl_conversation_duration.time_started", [$params['start_date'], $params['end_date']]);
    }

    /**
     * checks if there is ongoing-in duration record
     * if there is, it will update duration record
     * if not it will create duration record 
     */
    public static function create_autoresponse_duration_record(TblConversations $conversation, TblMessages $message, $bot, $discard = false){
        $cd = \TblConversationDuration::where("status", "ongoing-in")
                        ->where('conversation_id', $conversation->id)
                        ->get()
                        ->last();

        if($cd == NULL){
            $persona = $conversation->TblPersonas;
            $subscriber = $conversation->TblSubscribers;
            $service = $conversation->TblServices;

            $cd_data = [
                'conversation_id' => $conversation->id, 
                'time_started' => $message->date_created, 
                'status' => 'ongoing-in', 
                'last_inbound_message' => $message->message, 
                'last_inbound_time' => $message->bound_time, 
                'first_message_id' => $message->id,
                'last_message_id' => $message->id
            ];

            if($discard){
                $date = gmdate('Y-m-d H:i:s');
                $cd_data['user_id'] = $bot->id;
                $cd_data['status'] = 'ended';
                $cd_data['assigned_time'] = $date;
                $cd_data['time_ended'] = $date;
                $cd_data['time_fetched'] = $date;
                $cd_data['duration'] = strtotime($message->bound_time) - strtotime($date);
            }

            $cd = \TblConversationDuration::create($cd_data);

            $rcd_data = [
                'conversation_duration_id' => $cd->id, 
                'conversation_id' => $conversation->id, 
                'time_started' => $message->date_created, 
                'status' => $cd_data['status'], 
                'last_inbound_message' => $message->message, 
                'last_inbound_time' => $message->bound_time, 
                'first_message_id' => $message->id, 
                'last_message_id' => $message->id, 
                'persona_id' => $persona->id, 
                'persona_name' => $persona->name, 
                'subscriber_id' => $subscriber->id, 
                'subscriber_name' => $subscriber->name, 
                'service_id' => $service->id, 
                'service_name' => $service->name, 
                'date_created' => gmdate('Y-m-d H:i:s') 
            ];

            if($discard){
                $rcd_data['user_id'] = $bot->id;
                $rcd_data['fullname'] = $bot->firstname;
                $rcd_data['assigned_time'] = $cd->assigned_time;
                $rcd_data['time_ended'] = $cd->time_ended;
                $rcd_data['time_fetched'] = $cd->time_fetched;
                $rcd_data['duration'] = $cd->duration;
            }

            \RptConversationDuration::create($rcd_data);
        }
        else{
            $cd->last_inbound_message = $message->message;
            $cd->last_inbound_time = $message->bound_time;
            $cd->last_message_id = $message->id;
            $cd->save();

            \RptConversationDuration::where('conversation_duration_id', $cd->id)
                ->update([
                    'last_inbound_message' => $message->message, 
                    'last_inbound_time' => $message->bound_time,
                    'last_message_id' => $message->id,
                    ]);
        }
    }
}

