<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TblAutoresponseQueue extends Model {

    public $timestamps = false;

    protected $table = 'tbl_autoresponse_queue';
    protected $fillable = ['autoresponse_id', 'message_id', 'execution', 'executed', 'status', 'notes', 'date_created'];


    public function TblMessages() {
        return $this->belongsTo(\App\Models\TblMessages::class, 'message_id', 'id');
    }

    public function TblAutoresponses() {
        return $this->belongsTo(\App\Models\TblAutoresponses::class, 'autoresponse_id', 'id');
    }

    public function TblLibraryMessages() {
        return $this->belongsTo(\App\Models\TblLibraryMessages::class, 'library_message_id', 'id');
    }

	public static $assigned_time = null;

	public static function execute_pending(){
		$queue = \TblAutoresponseQueue::where('status','=','pending')
										->where('execution','<=',gmdate(\Config::get('application.date_format_php')) ) // LIVE CONFIG
										// ->where('execution','<=',gmdate('Y-m-d H:i:s',strtotime('+2 hour',strtotime(gmdate(\Config::get('application.date_format_php'))))) ) //LOCALHOST CONFIG
										->get();

		$executed = 0;
		if($queue->count() > 0){
			foreach($queue as $rule){
				$result = $rule->try_execute();
				$executed += $result == true ? 1 : 0;       
			}
		}
		return $executed;
	}

	protected function try_execute(){
		$bot = \TblUsers::where('username','=','bot')->first();
		$inbound_msg = $this->TblMessages;

		$cd = \TblConversationDuration::where('conversation_id', $inbound_msg->conversation_id)
				->whereIn('status', array('ongoing-in', 'ongoing-out'))
				->get()
				->last();

		$conversation = $this->process_queue($bot, $cd);
		$library_message = $this->TblLibraryMessages;
		
		$data = array(
			"subscriber_last_message_id" => $this->message_id,
			//"conversation_duration_id" => $this->TblMessagesMod->TblConversationsMod->TblConversationDurationMod->id,
			"conversation_id" => $conversation->id,
		);

		$outboundLib = new \Outbound();
		$outboundLib->send($data, $library_message->message, $bot);

		$autoresponded_message = new \TblAutorespondedMessages();
		$autoresponded_message->message_id = $this->message_id;
		$autoresponded_message->library_message_id = $library_message->id;
		$autoresponded_message->date_created = $this->date_created;
		$autoresponded_message->subscriber_id = $conversation->subscriber_id;
		$autoresponded_message->date_executed = gmdate(\Config::get('application.date_format_php'));
		$autoresponded_message->save();

		//end conversation duration
		$this->end_conversation_duration($bot, $cd);
		return true;
	}

	protected function process_queue($bot, $cd){
		if($cd != NULL){
			$fetch_time = gmdate(\Config::get('application.date_format_php'));

			$cd->time_fetched = $fetch_time;
			$cd->fetch_count += 1;
			$cd->save();

			\RptConversationDuration::where('conversation_duration_id', $cd->id)
				->update([
						'time_fetched' => $fetch_time,
						'fetch_count' => $cd->fetch_count,
					]);
		}

		self::$assigned_time = gmdate(\Config::get('application.date_format_php'));
		$inbound_msg = $this->TblMessages;
		$inbound_msg->assigned_time = self::$assigned_time;
		$inbound_msg->user_id = $bot->id;
		$inbound_msg->fetched = 1;
		$inbound_msg->status = 'handled';
		$inbound_msg->conversation_id = $inbound_msg->conversation_id;
		$inbound_msg->save();

		\RptMessages::where('message_id', $this->message_id)
			->update([
				'assigned_time' => self::$assigned_time,
				'user_id' => $bot->id,
				'fetched' => 1,
				'status' => 'handled',
			]);

		//\TblSubscriberBilling::end_subscriber_billing($inbound_msg->conversation_id, $bot);
		$this->delete();
		return $inbound_msg->TblConversations;
	}

	protected function end_conversation_duration($bot, TblConversationDuration $cd){
		if($cd != NULL){
			$end_time = gmdate(\Config::get('application.date_format_php'));
			$duration_time = \DateTimeHelper::duration($cd->time_started, $end_time);

			$cd->user_id = $bot->id;
			$cd->assigned_time = self::$assigned_time;
			$cd->time_ended = $end_time;
			$cd->duration = $duration_time;
			$cd->status = 'ended';
			$cd->save();

			\RptConversationDuration::where('conversation_duration_id', $cd->id)
				->update([
						'user_id' => $bot->id,
						'fullname' =>  $bot->full_name(2),
						'assigned_time' => self::$assigned_time,
						'time_ended' => $end_time,
						'duration' => $duration_time,
						'status' => "ended"
					]);
		}
	}
}
