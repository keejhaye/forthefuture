<?php namespace App\libraries;

class InboundQueue {

	var $inbound_time = null;
	var $is_autoresponse = FALSE;
	var $subscriber_limits = array();

	public function process_inbound_message($post, $inbound_time){
		$data = json_decode($post, true);
		\Session::set("inbound-process", "");
		$this->inbound_time = $inbound_time;     //set inbound time, use this for all record, for consistency    
		$status = $data["status"];

		$conversation_record = \TblConversations::fetch_conversation($data["subscriber_id"], $data["persona_id"], $data["service_id"], $status["conversation"]);//find conversation, create one if no record found    

		$message_status = $this->set_message_status($status['message']);
		$new_message = $this->new_message($conversation_record["conversation"], $data["message"], $message_status, isset($data["additional_info"]) ? $data["additional_info"] : null, isset($data['attachment']) ? $data['attachment'] : null);

		if($status["message"] == "pending"){
			$autoresponse_checking = $this->auto_response_checking($conversation_record, $data, $status, $new_message);

			if($autoresponse_checking == false){
			    $publish_data = array(
			      'id' => $new_message->id,
			      'conversation_id' => $conversation_record["conversation"]->id,
			      'service_id' => $data['service_id'],
			      'persona_id' => $data['persona_id'],
			      'subscriber_id' => $data['subscriber_id'],
			      'message' => $data['message'],
			    );
			    
			    \Redis::publish('api-inbound',json_encode($publish_data, JSON_PRETTY_PRINT));
			}
			$this->start_subscriber_billing($new_message, $conversation_record['conversation']);
		}

		$logs = \Session::get("inbound-process");
		\Session::forget("inbound-process");
		return true;
	}

	protected function set_message_status($status){
		$message_status = $status;
		switch ($status) {
			case "pending":
				$message_status = "normal";
				break;
			case "discard":
				$message_status = "discard";
				break;
			default:
				$message_status = "normal";        
				break;
		}

		return $message_status;
	}

	protected function new_message($conversation, $message, $status = "normal", $additional_info = null, $attachment = null){
		$log_status = null;
		$msg = new \TblMessages();
		$msg->conversation_id = $conversation->id;

		if($message == "")
			$message = "( no message )";

		$msg->message = $message;
		$msg->bound_time = $this->inbound_time;
		$msg->direction = 'inbound';
		$msg->status = $status;
		$msg->date_created = gmdate(\Config::get("application.date_format_php"));
		$msg->save();
		$rptMessages = new \RptMessages();

		if($additional_info != null && $additional_info != ""){
			if(isset($additional_info["billed"])){
				$info = $additional_info["billed"];
				if($info === "true" || $info === true || $info === 1 || $info === "1"){
					$msg->is_billed = 1;
					$rptMessages->is_billed = 1;
					$additional_info["billed"] = true;          
				}
				else if($info === "false" || $info === false || $info === 0 || $info === "0"){
					$msg->is_billed = 0;
					$rptMessages->is_billed = 0;
					$additional_info["billed"] = false;
				}
				else{
					$msg->is_billed = 0;
					$rptMessages->is_billed = 0;
					$additional_info["billed"] = false;          
				}
			}      
			$msg->additional_info = json_encode($additional_info);
		}
		$rptMessages->conversation_id = $conversation->id;
		$rptMessages->persona_id = $conversation->persona_id;
		$rptMessages->persona_name = $conversation->persona_name;
		$rptMessages->subscriber_id = $conversation->subscriber_id;
		$rptMessages->subscriber_name = $conversation->subscriber_name;
		$rptMessages->service_id = $conversation->service_id;
		$rptMessages->service_name = $conversation->service_name;
		$rptMessages->bound_time = $this->inbound_time;
		$rptMessages->direction = "inbound";
		$rptMessages->status = $status;
		$rptMessages->date_created = gmdate(\Config::get("application.date_format_php"));
		$msg->RptMessages()->save($rptMessages);

		if(isset($attachment) && $attachment != null && count($attachment) > 0){
			$images = $attachment;
			$data_to_insert = array();
			for($x = 0; $x < sizeOf($images); $x++){  
				array_push($data_to_insert, [
					'message_id' => $msg->id,
					'path' => $images[$x],
					'date_created' => gmdate("Y-m-d H:i:s"),
					'expire_on' => gmdate("Y-m-d H:i:s", strtotime('30 days'))
				]);
			}
			\TblInboundMessageAttachment::insert($data_to_insert);
		}
		else{
			\TblInboundMessageAttachment::insert([
					'message_id' => $msg->id,
					'path' => $attachment,
					'date_created' => gmdate("Y-m-d H:i:s"),
					'expire_on' => gmdate("Y-m-d H:i:s", strtotime('30 days'))
			]);
		}

		if($status == "discard"){
			$log_status = "Message is discarded";
			$log[] = ["time"=> gmdate("Y-m-d H:i:s"), "message" => "Conversation instance has ended with status: ".$status];
		}

		$log[] = ["time"=> gmdate("Y-m-d H:i:s"), "message" => "A new message has arrived"];
		if($log_status != null)
		$log[] = ["time"=> gmdate("Y-m-d H:i:s"), "message" => $log_status];

		\LogHelper::log_array_to_session("inbound-process", $log, true);    

		return $msg;
	}

	protected function auto_response_checking($conversation_record, $post, $status, $new_message){
		$autoresponse_sent = false;
		if($conversation_record['conversation']->status != 'assigned'){ //If conversation is 'pending' or 'handled', proceed with autoresponse

			$service_and_persona_rules = \TblAutoresponses::get_service_and_persona_rules($conversation_record['conversation']->persona_id, $conversation_record['conversation']->service_id);

			if(count($service_and_persona_rules) == 0)
				return $autoresponse_sent;

			$conditions = $this->get_autoresponse_condition($conversation_record["conversation"], $post["message"], $service_and_persona_rules);

			if(empty($conditions))
				return $autoresponse_sent;

			$this->queue_autoresponse($new_message, $conditions, $conversation_record['conversation']->subscriber_id);

		}
		return $autoresponse_sent;
	}

	protected function get_autoresponse_condition($conversation, $message, $service_and_persona_rules){

		$conversation_has_no_message = \TblConversations::has_no_message($conversation->id);

		$conditions = array();
		foreach($service_and_persona_rules as $rule)
		{
			$keyword = strtolower($rule->keyword);
			switch($rule->message_condition){
				case 'first message':
					if($conversation_has_no_message){ //CHECK IF FIRST MESSAGE
						array_push($conditions, $rule);
					}
					break;
				case 'first message contains keyword':
					if($conversation_has_no_message){ //CHECK IF FIRST MESSAGE
						$match = preg_match("/\b{$keyword}\b/i", $message); //CHECK IF THE MESSAGE 'CONTAINS' MATCHES THE KEYWORD
						if($match == 1)
							array_push($conditions, $rule);
					}
					break;
				case 'first message equals keyword':
					if($conversation_has_no_message){  //CHECK IF FIRST MESSAGE
						if(trim(strtolower($message)) == $keyword) //CHECK IF THE MESSAGE 'EXACTLY' MATCHES THE KEYWORD
							array_push($conditions, $rule);
					}
					break;
				case 'message contains':
					$match = preg_match("/\b{$keyword}\b/i", $message); //CHECK IF THE MESSAGE 'CONTAINS' MATCHES THE KEYWORD
					if($match == 1)
					  array_push($conditions, $rule);
					break;
				case 'message equals keyword':
					if(trim(strtolower($message)) == $keyword) //CHECK IF THE MESSAGE 'EXACTLY' MATCHES THE KEYWORD
					  array_push($conditions, $rule);
					break;
			}
		}
		return $conditions;
	}

	protected function start_subscriber_billing($message, $conversation){
		if($conversation->enable_subscriber_billing){    
			$pending_billing = \TblPendingSubscriberBilling::get_pending_billing(array("conversation_id" => $conversation->id));   
			if(empty($pending_billing)){
				$sb = new \TblSubscriberBilling();
				$sb->conversation_id = $conversation->id;
				$sb->subscriber_id = $conversation->subscriber_id;
				$sb->start_time = $message->bound_time;
				$sb->date_created = gmdate(\Config::get("application.date_format_php"));
				$sb->save();             
				
				$psb = new \TblPendingSubscriberBilling();
				$psb->date_created = gmdate(\Config::get("application.date_format_php"));
				$sb->TblPendingSubscriberBilling()->save($psb);

				$rsb = new \RptSubscriberBilling();
				$rsb->conversation_id = $conversation->id;
				$rsb->subscriber_id = $conversation->subscriber_id;
				$rsb->subscriber_name = $conversation->subscriber_name;
				$rsb->service_id = $conversation->service_id;
				$rsb->service_name = $conversation->service_name;
				$rsb->start_time = $message->bound_time;
				$rsb->date_created = gmdate(\Config::get("application.date_format_php"));
				$sb->RptSubscriberBilling()->save($rsb);
			}
		}    
	}
  
	protected function queue_autoresponse($new_message, $rules, $subscriber_id){
		$data_to_insert = array();
		foreach ($rules as $rule) {
			$next_library_message = \TblLibraryMessages::get_next_library_message($subscriber_id, $rule); 
			if(!empty($next_library_message)){
				$next_library_message = $next_library_message[0];
				$autoresponse_queue_entry = [
					'library_message_id' => $next_library_message->id,
					'message_id' => $new_message->id,
					'status' => "pending"
				];
				if($rule->delay == '0'){
					$autoresponse_queue_entry['execution'] = gmdate(\Config::get('application.date_format_php'));
				}
				else{
					$current_date = new DateTime();
					$current_date->add(new DateInterval('PT' . $rule->delay . 'S'));
					$current_date = $current_date->format(\Config::get('application.date_format_php'));
					$autoresponse_queue_entry['execution'] = $current_date;
				}
				$autoresponse_queue_entry['date_created'] = gmdate(\Config::get('application.date_format_php'));
				array_push($data_to_insert, $autoresponse_queue_entry);
			}
		}
		\TblAutoresponseQueue::insert($data_to_insert);
	}
}
