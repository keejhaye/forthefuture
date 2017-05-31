<?php
namespace App\Libraries;

use DateTime;
use DateInterval;

class Inbound {

    var $inbound_time = null;
    var $is_autoresponse = FALSE;
    var $subscriber_limits = array();

    public function receive($post, $service, $subscriber, $persona){
        $inbound_time = gmdate("Y-m-d H:i:s");  
       
        
        if(empty($subscriber)){
            $result["status"] = false;
            $result["code"] = "506";
            $result["message"] = "Subscriber not found in service";
            return $result;
        }

       if(empty($persona)){
          $result["status"] = false;
          $result["code"] = "506";
          $result["message"] = "Persona not found in service";
          return $result;
        }

        // For attachment
        if(isset($post['attachment']) && $post['attachment'] !== ''){
            $attachment = $this->validate_attachment($post['attachment']);
            if($attachment !== true){
                $result["status"] = false;
                $result["code"] = "507";
                $result["message"] = $attachment;
                return $result;
            }
        }
        $post["service_id"] = $service->id;
        $post["persona_id"] = $persona->id;
        $post["subscriber_id"] = $subscriber->id;
        $post["timezone"] = $service->timezone;
        $post["limits"] = $this->process_subscriber_limits($service);
        $post["status"] = $this->subscriber_limit_checker($post);  

        $blacklist = $this->check_blacklist($subscriber->id);    
        if($blacklist)
            return $blacklist;    
        //no error message beyond this point
        
        $autodiscard = $this->autodiscard_checker($post);

        $messagediscard = $this->messagediscard_checker($post);

        if($autodiscard == "discard" || $post["status"]["message"] ==  "discard" || $messagediscard == "discard")
            $post["status"] = "discard";
        
        // if($service->id == 37)
        //     log::logfile(json_encode($post), "CASH-IM-logs", "traces");

        //PROCESS REQUEST RIGHT AWAY. NOT QUEUEING
        $encoded_data = json_encode($post);
        $inboundLib = new \InboundExecution();
        $inboundLib->process($post, $service, $subscriber, $persona);

        //IF SWITCH TO MSG QUEUEING, UNCOMMENT THESE LINES
        // $queue = new \TblMessagesQueue();
        // $queue->details = json_encode($post);    
        // $queue->date_created = gmdate("Y-m-d H:i:s");
        // $queue->inbound_time = $inbound_time;
        // $queue->service_id = $service->id;
        // $queue->save();
       
        $service->last_inbound_time = $inbound_time;
        $service->save();

        if(\Config::get('curl.enable_send_to_loopsnoop')){
            \UtilHelper::send_curl('http://10.42.74.68:3000/command/im3/inbound_message', $encoded_data);
        }

        $result["status"] = true;
        return $result;
    }

    protected function subscriber_limit_checker($data){
        $status["message"] = "pending"; 
        $status["conversation"] = "pending";

        $subscriber_limit = $this->has_subscriber_limit_reached($data);

        if($subscriber_limit["message"] && $subscriber_limit["persona"])
            $status["message"] = "discard";
        else if($subscriber_limit["message"])
            $status["message"] = "discard";    
        else if($subscriber_limit["persona"])
            $status["message"] = "discard";

        if($status["message"] == "discard")
            $status["conversation"] == "handled";

        return $status;
    }

    protected function get_subscriber_limits($limits){
        if(count($limits) > 0){
            foreach ($limits as $key => $value) {
                $this->process_subscriber_limits($value);
            }
        }
        return $this->subscriber_limits;
    }

    protected function process_subscriber_limits($service){

        //service message limit
        if($service->message_limit_reset_period == "1 day" ){
            $this->subscriber_limits["subscriber_message_limit"]["day"]["limit"] = $service->message_limit;
            $this->subscriber_limits["subscriber_message_limit"]["day"]["in_seconds"] = strtotime($service->message_limit_reset_period,0);
            $this->subscriber_limits["subscriber_message_limit"]["day"]["action"] = $service->message_limit_action;
        }
        else if($service->message_limit_reset_period == "1 month"){
            $this->subscriber_limits["subscriber_message_limit"]["month"]["limit"] = $service->message_limit;
            $this->subscriber_limits["subscriber_message_limit"]["month"]["in_seconds"] = strtotime($service->message_limit_reset_period,0);
            $this->subscriber_limits["subscriber_message_limit"]["month"]["action"] = $service->message_limit_action; 
        }

        //service persona limit
        if($service->persona_limit_reset_period == "1 day"){
            $this->subscriber_limits["subscriber_persona_limit"]["day"]["limit"] = $service->persona_limit;
            $this->subscriber_limits["subscriber_persona_limit"]["day"]["in_seconds"] = strtotime($service->persona_limit_reset_period,0);
            $this->subscriber_limits["subscriber_persona_limit"]["day"]["action"] = $service->persona_limit_action;        
        }
        else if($service->persona_limit_reset_period == "1 month"){
            $this->subscriber_limits["subscriber_persona_limit"]["month"]["limit"] = $service->persona_limit;
            $this->subscriber_limits["subscriber_persona_limit"]["month"]["in_seconds"] = strtotime($service->persona_limit_reset_period,0);
            $this->subscriber_limits["subscriber_persona_limit"]["month"]["action"] = $service->persona_limit_action;                
        }

        return $this->subscriber_limits;      
    }

    public function validate_attachment($attachment){
        $image = "";
        if(is_array($attachment)){
            $data = "";
            foreach($attachment as $string){
                $data .= $string . ",";
            }
            $attachment = rtrim($data,",");
        }

        if(strpos($attachment,",") !== false && is_array($attachment) == false){
            $image = explode(",", $attachment);
        }
        else{
            $image = array($attachment);
        }

        foreach ($image as $url) {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_exec($ch);
            switch (curl_getinfo($ch, CURLINFO_CONTENT_TYPE)) { //validate file type
                case 'image/png' :
                case 'image/jpg' :
                case 'image/jpeg':
                case 'image/gif' :
                  return true;
                  break;
                default:
                  return "Invalid attachment file type.";
                  break;
            }
        }
    }

    protected function has_subscriber_limit_reached($data){
        $limit = array("message" => false, "persona" => false);

        if(isset($data["limits"]['subscriber_message_limit'])){
            $sub_message_limit = \TblSubscriberMessageLimit::where('subscriber_id',$data["subscriber_id"])->first();


            if(isset($data["limits"]['subscriber_message_limit']["day"]) && $data["limits"]['subscriber_message_limit']["day"]["limit"] > 0
            && !isset($data["limits"]['subscriber_message_limit']["month"]))        
                $limit["message"] = $this->process_subscriber_message_limit($sub_message_limit, $data, "day");      

            else if(isset($data["limits"]['subscriber_message_limit']["month"]) && $data["limits"]['subscriber_message_limit']["month"]["limit"] > 0
            && !isset($data["limits"]['subscriber_message_limit']["day"]))
                $limit["message"] = $this->process_subscriber_message_limit($sub_message_limit, $data, "month");      

            else if(isset($data["limits"]['subscriber_message_limit']["day"]) && $data["limits"]['subscriber_message_limit']["day"]["limit"] > 0
            && isset($data["limits"]['subscriber_message_limit']["month"]) && $data["limits"]['subscriber_message_limit']["month"]["limit"] > 0)
                $limit["message"] = $this->process_subscriber_message_limit($sub_message_limit, $data, "both");      
        }

        if(isset($data["limits"]['subscriber_persona_limit'])){
            if(isset($data["limits"]['subscriber_persona_limit']["day"]) && $data["limits"]['subscriber_persona_limit']["day"]["limit"] > 0
            && !isset($data["limits"]['subscriber_persona_limit']["month"]))        
                $limit["persona"] = $this->process_subscriber_persona_limit($data, "day");      

            else if(isset($data["limits"]['subscriber_persona_limit']["month"]) && $data["limits"]['subscriber_persona_limit']["month"]["limit"] > 0
            && !isset($data["limits"]['subscriber_persona_limit']["day"]))
                $limit["persona"] = $this->process_subscriber_persona_limit($data, "month");      

            else if(isset($data["limits"]['subscriber_persona_limit']["day"]) && $data["limits"]['subscriber_persona_limit']["day"]["limit"] > 0
            && isset($data["limits"]['subscriber_persona_limit']["month"]) && $data["limits"]['subscriber_persona_limit']["month"]["limit"] > 0)
                $limit["persona"] = $this->process_subscriber_persona_limit($data, "both");       
        }

        return $limit;
    }

    protected function process_subscriber_persona_limit($data, $case){
        // echo json_encode($data['persona_id']);
        $limit = false;
        $limit_day = false;
        $limit_month = false;
        $is_persona_already_chatted = false;

        if($case == "day" || $case == "both"){
            $sub_persona_limit_day = \TblSubscriberPersonaLimit::get_subscriber_persona_limit($data["subscriber_id"], "day");      

            if($sub_persona_limit_day->count() >= $data["limits"]["subscriber_persona_limit"]["day"]["limit"]){

                foreach ($sub_persona_limit_day as $personas) {
                    if ($data['persona_id'] == $personas['persona_id']) {
                        $is_persona_already_chatted = true;
                    }
                }

                if ($is_persona_already_chatted) {
                    $this->add_subscriber_persona_limit($data, "day"); 
                }else {
                    $limit_day = true;  
                }
            }   
            else {
                $this->add_subscriber_persona_limit($data, "day");      
            }
        }

        if($case == "month" || $case == "both"){
            $sub_persona_limit_month = \TblSubscriberPersonaLimit::get_subscriber_persona_limit($data["subscriber_id"], "month");      
            if($sub_persona_limit_month->count() >= $data["limits"]["subscriber_persona_limit"]["month"]["limit"]){       
                $limit_month = true;     
            }
            else 
                $this->add_subscriber_persona_limit($data, "month");           
        }

        if($limit_day || $limit_month)
            $limit = true;

        return $limit;
    }

    protected function process_subscriber_message_limit($sub_message_limit, $data, $case){
        $limit = false;
        if($sub_message_limit != NULL){
            $limit = $this->subscriber_message_action($sub_message_limit, $data, $case);
        }
        else
            $this->add_subscriber_message_limit($data, $case);            

        return $limit;
    }

    protected function subscriber_message_action($record, $data, $period){
        $limit = false;
        $limit_day = false;
        $limit_month = false;

        if($period == "day" || $period = "both"){
            if(isset($data["limits"]["subscriber_message_limit"]["day"])){
                if($record->messages_count < $data["limits"]["subscriber_message_limit"]["day"]["limit"]){
                    $record->messages_count += 1;
                    $record->save();
                }
                else
                    $limit_day = true;                     
            }      
        }

        if(isset($data["limits"]["subscriber_message_limit"]["month"])){
            if($record->messages_count_monthly < $data["limits"]["subscriber_message_limit"]["month"]["limit"]){
                $record->messages_count_monthly += 1;
                $record->save();
            }
            else
            $limit_month = true;        
        }    

        if($limit_day || $limit_month)
            $limit = true;

        return $limit;
    }  

    protected function check_blacklist($subscriber_id){
        $blacklist = \TblBlacklist::where('subscriber_id',$subscriber_id)->first();
        if($blacklist){
            $result["status"] = false;
            $result["code"] = "514";
            $result["message"] = "Subscriber is blacklisted";
            return $result;
        }
        return false;
    }

    protected function add_subscriber_message_limit($data, $period = null){
        $model = new \TblSubscriberMessageLimit();
        $model->subscriber_id = $data["subscriber_id"];

        if($period == "day" || $period = "both"){
            if(isset($data["limits"]["subscriber_message_limit"]["day"])){
                $model->messages_count = 1;
                $model->reset_on = \DateTimeHelper::reset_date($data["limits"]["subscriber_message_limit"]["day"]["in_seconds"], 
                                                        $data["timezone"],
                                                        $this->inbound_time, true);              
            }   
        }

        if ($period == "month" || $period = "both"){
            if(isset($data["limits"]["subscriber_message_limit"]["month"])){
                $model->messages_count_monthly = 1;
                $model->monthly_reset_on = \DateTimeHelper::reset_date($data["limits"]["subscriber_message_limit"]["month"]["in_seconds"], 
                                                      $data["timezone"],
                                                      $this->inbound_time, true);              
            }
        }

        $model->date_created = gmdate(\Config::get("application.date_format_php"));
        $model->save();
        return $model;
    }

    protected function add_subscriber_persona_limit($data, $period){
        $record = \TblSubscriberPersonaLimit::find_subscriber_persona_record($data["subscriber_id"], $data["persona_id"], $period);
        if($record->count() === 0){
            $model = new \TblSubscriberPersonaLimit();
            $model->subscriber_id = $data["subscriber_id"];
            $model->persona_id = $data["persona_id"];
            $model->reset_on = \DateTimeHelper::reset_date($data["limits"]["subscriber_persona_limit"][$period]["in_seconds"], 
                                                    $data["timezone"],
                                                    $this->inbound_time, true);
            $model->date_created = gmdate(\Config::get("application.date_format_php"));
            $model->reset_period = $period;
            $model->save();
            return $model;      
        }

        return $record;
    }

    protected function autodiscard_checker($data){
        $status = false;
        $records = \TblAutoDiscard::where('service_id',$data["service_id"])->get();

        $time = \DateTimeHelper::get_date("Y-m-d H:i:s", null, $data["timezone"]);
        if(count($records) > 0){
            foreach($records as $key => $value){
                $status = $this->autodiscard_checking_process($value, $time, $data["timezone"]);
                if($status == 'discard')
                    return $status;
            }
        }
        return $status;
    } 

    protected function autodiscard_checking_process($value, $time, $timezone){
        $status = "normal";
        $today = \DateTimeHelper::get_date("Y-m-d", null, $timezone);
        $tomorrow = \DateTimeHelper::get_date("Y-m-d", ("+1 day"), $timezone);      
        $start = $today." ".$value->start_time;

        $dateNow = $this->get_converted_date($timezone, $time);
        $auto_discard_start_date = $this->get_converted_date($timezone, $value->start_time);
        $auto_discard_end_date = $this->get_converted_date($timezone, $value->end_time);
        echo $time . "<br>";
        echo "start_time ".$auto_discard_start_date . "<br>";
        echo "end_time ".$auto_discard_end_date . "<br>";
        echo \DateTimeHelper::get_date("Y-m-d H:i:s", strtotime($value->start_time), -$timezone);

        if($value->start_time < $value->end_time){
            $end = $today." ".$value->end_time;
            if($time >= $start && $time <= $end)
                $status = "discard";
        }
        else{
            $end = $tomorrow." ".$value->end_time;
            if($time >= $start && $time <= $end)
                $status = "discard";
        }

        return $status;
    }

    protected function get_converted_date($timezone, $dateToBeConverted){
        $timezoneChosen = $timezone;
        $addorSub = substr($timezoneChosen, 0, 1);
        $getMin = "00";

        $date = new DateTime($dateToBeConverted);

        if ($timezoneChosen != "0.0") {
            if ($addorSub != "-") {
                if (intval($timezoneChosen) < 0) {
                    $trueHour = 0 - intval($timezoneChosen);
                    $date->sub(new DateInterval('PT'.$trueHour.'H'.$getMin.'M'));
                }else if(intval($timezoneChosen) > 0) {
                    $trueHour = intval($timezoneChosen) - 0;
                    $date->add(new DateInterval('PT'.$trueHour.'H'.$getMin.'M'));
                }
            }else {
                $trueMuniVal = intval($timezoneChosen) + 0;
                $date->sub(new DateInterval('PT'.$trueMuniVal.'H'.$getMin.'M'));
            }
                return $date->format('Y-m-d H:i:s');
        }else {
            return $date->format('Y-m-d H:i:s');
        }
    }

    protected function messagediscard_checker($data){
        $status = "normal";
        $record = \TblServices::where('id',$data["service_id"])->first();
        
        if($record->enable_message_discard == 1){
            $status = $this->messagediscard_checking_process($data);
        }
        return $status;
    } 

     protected function messagediscard_checking_process($value){
       $status = "normal";

     $messages = \TblMessagesToDiscard::where('service_id',$value["service_id"])->get();

    if(count($messages) > 0){
      foreach($messages as $key => $message){

        if (strpos(strtolower($value['message']), strtolower($message->phrase)) !== false) {
          $status = "discard";
        }
      }
    }
  return $status;
}
}