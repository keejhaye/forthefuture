<?php namespace App\libraries {

    class Outbound {

      public function send($data, $message, $user = null, $outbound_time = null, $inbound_data = null, $image = array(), $path="", $expiry="") {
		    if($inbound_data == null)
		      $inbound_data = \TblMessages::get_relational_inbound_object($data["subscriber_last_message_id"]);
		    
		    if($outbound_time == null)
		      $outbound_time = gmdate("Y-m-d H:i:s");

		    $duration_time = \DateTimeHelper::duration($inbound_data->assigned_time, $outbound_time);
		    if(empty($user)){
		      $user = \TblUsers::find(\Session::get('user.id'));
		    }

		    $outbound = new \TblMessages();
		    $rptMessage = new \RptMessages();
		    $aggregatorResponse = new \TblAggregatorResponses();
		    
		    $outbound->user_id = $user->id;
		    $outbound->conversation_id = $data["conversation_id"];    
		    $outbound->message = $message;
		    $outbound->direction = "outbound";
		    $outbound->bound_time = $outbound_time;
		    $outbound->assigned_time = $outbound_time;
		    $outbound->duration = $duration_time;
		    $outbound->date_created = gmdate("Y-m-d H:i:s");
		    $outbound->ip_address = $_SERVER['REMOTE_ADDR'];

		    if($inbound_data->additional_info != "" || $inbound_data->additional_info != null){

		      $billed = json_decode($inbound_data->additional_info, true);
		      if(isset($billed["billed"])){
		        if($billed["billed"] || $billed["billed"] == "true"){
		          $outbound->is_billed = 1;
		          $rptMessage->is_billed = 1;                        
		          $billed["billed"] = true;
		        }
		        else if(!$billed["billed"] || $billed["billed"] == "false"){
		          $outbound->is_billed = 0;
		          $rptMessage->is_billed = 0;                        
		          $billed["billed"] = false;
		        }
		      }
		      if(!$billed){
        $billed = array();
      }
        $billed["operatorId"] = $user->username;
		      $outbound->additional_info = json_encode($billed);
		    }


		    $outbound->save();

		    $rptMessage->conversation_id = $inbound_data->conversation_id;
		    $rptMessage->persona_id = $inbound_data->persona_id;
		    $rptMessage->persona_name = $inbound_data->persona_name;
		    $rptMessage->subscriber_id = $inbound_data->subscriber_id;
		    $rptMessage->subscriber_name = $inbound_data->subscriber_name;
		    $rptMessage->service_id = $inbound_data->service_id;
		    $rptMessage->service_name = $inbound_data->service_name;
		    $rptMessage->bound_time = $outbound_time;
		    $rptMessage->assigned_time = $outbound_time;
		    $rptMessage->direction = "outbound";
		    $rptMessage->duration = $duration_time;
		    $rptMessage->user_id = $user->id;
		    $rptMessage->fullname = $user->full_name(2);
		    $rptMessage->date_created = gmdate("Y-m-d H:i:s"); 
		    $outbound->RptMessages()->save($rptMessage);

		    $outbound_additional_info = array(
		    	"conversation_id" => $inbound_data->conversation_id,
		    	"operatorId" => $user->username
		    	);
		    $aggregatorResponse->service_id = $inbound_data->service_id;
		    $aggregatorResponse->target_url = $inbound_data->aggregator_url;
		    $aggregatorResponse->message_id = $data["subscriber_last_message_id"];
		    $aggregatorResponse->meta = json_encode($this->format_outbound($inbound_data, $message, $image, $path,$inbound_data->conversation_id, $outbound_additional_info));
		    $aggregatorResponse->execution = gmdate("Y-m-d H:i:s");
		    $aggregatorResponse->date_created = gmdate("Y-m-d H:i:s");
		    $outbound->TblAggregatorResponses()->save($aggregatorResponse);

		    //TOTEST
		    if( !empty($image) && sizeOf($image) != 0){
					$data_to_insert = array();
					$max = sizeOf($image);
					$upload_dir = base_path(). $path;

					for($x = 0; $x < $max; $x++){
						array_push($data_to_insert, [
							'file' => strtotime(gmdate("Y-m-d H:i:s")) . "_" .str_replace(" ", "", $image[$x]->getClientOriginalName()),
							'path' => url('/')."/{$path}",
							'message_id' => $outbound->id,
							'date_created' => gmdate("Y-m-d H:i:s"),
							'expire_on' => gmdate("Y-m-d H:i:s", strtotime('+'.$expiry.' days'))
						]);
						$image[$x]->move($path, $data_to_insert[$x]['file']);
					}
					\TblOutboundMessageAttachments::insert($data_to_insert);
		    }

		    \TblConversationDuration::update_outbound_conversations([
		    	'conversation_id' => $inbound_data->conversation_id,
		    	'last_outbound_message' => $message, 
		    	'last_outbound_time' => $outbound_time, 
		    	'last_message_id' => $outbound->id, 
		    	'status' => 'ongoing-out'
		    ]);

	    //get all message ids assigned to user to be handled
	    $ids = array();
        $data = \TblMessages::select('id')
            ->where('conversation_id', $inbound_data->conversation_id)
            ->where('user_id', $user->id)
            ->where('status', 'assigned')
            ->get()
            ->toArray();

        foreach ($data as $row) {
            array_push($ids, $row['id']);
        }

		    \TblMessages::whereIn('id', $ids)
		    	->update([
  					'user_id' => $user->id,
  					'status' => 'handled',
  					'duration' => $duration_time,
  				]);

		    // $inbound_data->status = 'handled';
		    // $inbound_data->duration = $duration_time;
		    // $inbound_data->save();

		    \RptMessages::whereIn('message_id', $ids)
		    	->update([
  					'user_id' => $user->id,
  					'duration' => $duration_time,
  					'fullname' => $user->full_name(2)
  				]);

		    $logs[] = array("time"=> gmdate("Y-m-d H:i:s"), "message" => "A reply has been sent by {$user->full_name(4)}");
		    $logs[] = array("time"=> gmdate("Y-m-d H:i:s"), "message" => "Conversation instance status: ongoing-out");
		    $outbound->Inbound = $inbound_data;
		    return $outbound;
      }

		protected function format_outbound($inbound, $outbound, $image, $path, $conversation_id, $additional_info = array()){

			$service = \TblServices::where('code',$inbound->code)->first();
			$meta["username"] = $inbound->listener_username;
			$meta["password"] = $inbound->listener_password;
			$meta["service_code"] = $inbound->code;
			$meta["service_name"] = $service->name;
			$meta["from"] = $inbound->persona_name;
			$meta["to"] = $inbound->subscriber_name;
			$meta["message"] = $outbound;

			$file = "";

			if($image != "" && sizeOf($image) != 0){
				for($x = 0; $x < sizeOf($image); $x++){
					$file = strtotime(gmdate("Y-m-d H:i:s")) . "_" .str_replace(" ", "", $image[$x]->getClientOriginalName());
					// $file .= $path.$image[$x].",";
				}
				$file = rtrim($file,",");
			}

    $meta["attachment"] = "{".$file."}";


			if($inbound->additional_info != null && $inbound->additional_info != ""){
				$meta["additional_info"] = array_merge(json_decode($inbound->additional_info, true), $additional_info);
			return $meta;	
			}else{
				$meta["additional_info"] = $additional_info;
				return $meta;
			}
			
		}
  }
}