<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Users extends Controller {
  public function index(){

}

public function add(Request $request){
  $ip = $_SERVER['REMOTE_ADDR'];
  \Benchmark::start("api");
        if (\UtlInboundRequestLimiter::reached_limit($ip)) {
            \ApiHelper::request_limit_error("users");
            \UtlInboundRequestLimiter::increase($ip);
        }
          if (!isset($_POST["data"])) {
            $this->log_id = \ApiHelper::log_inbound_request($_POST, url('/'), $ip);
            \ApiHelper::format_error($this->log_id, "users");
        }

          $post_str = $_POST["data"];
        $this->log_id = \ApiHelper::log_inbound_request($post_str, url('/'), $ip);

        \ApiHelper::validate_json_format($post_str, $this->log_id, "users");
        $validation = \ApiHelper::validate_parameters($post, \Config::get('api.users_parameters'));
          $post = \ApiHelper::sanitize_data(json_decode($post_str, true));
       
        

        if ($validation["authentication"]["code"] == 0) {
            $service = \TblServices::where('code', $post['service_code'])->first();
            
            $creds = \ApiHelper::check_credentials($post["username"], $post["password"],$service, $this->log_id, $ip, "users");

            $service_id = $service->id;
            if (!$creds) {
                \ApiHelper::authentication_error($this->log_id, $ip, "users");
            } 
            else {
                $result = \ApiHelper::authentication_success();
                $receive = $this->add_process($post, $service_id);
                $result['transaction'] = $receive;
                $response = json_encode($result);
                \ApiHelper::save_inbound_response($this->log_id, $response, "users");
                echo $response;
            }
        } 
        else {
            $response = json_encode($validation);
            \ApiHelper::save_inbound_response($this->log_id, $response, "users", 1, $post["service_code"]);
            echo $response;
        }

}
public function update() {

    $ip = $_SERVER['REMOTE_ADDR'];
 
    Benchmark::start("api");
    if(\UtlInboundRequestLimiter::reached_limit($ip)) api::request_limit_error("users");
    \UtlInboundRequestLimiter::increase($ip);

    if(!isset($_POST["data"])){
      $this->log_id = \ApiHelper::log_inbound_request(json_encode($_POST), url('/'), $ip);
     \ApiHelper::format_error($this->log_id, "users");
    }
    
    $post_str = $_POST["data"];
    $this->log_id = \ApiHelper::log_inbound_request($post_str, url::current(), $ip);
    \ApiHelper::validate_json_format($post_str, $this->log_id, "users");    
    $post = \ApiHelper::sanitize_data(json_decode($post_str, true));    
    $validation = \ApiHelper::validate_parameters($post, \Config::get('api.users_parameters'));
    
    if($validation["authentication"]["code"] == 0){
      $creds = \ApiHelper::check_credentials($post["username"], $post["password"], $post["service_code"], $this->log_id, $ip, "users");  
      if(!$creds)
        \ApiHelper::authentication_error($this->log_id, $ip, "users");      
      else{
        $result = \ApiHelper::authentication_success();
        $update = $this->update_process($post);
        $result["transaction"] = $update;          
        $response = json_encode($result);
        \ApiHelper::save_inbound_response($this->log_id, $response, "users", 0);
        die($response);
      }
    }
    else{
      $response = json_encode($validation);
      \ApiHelper::save_inbound_response($this->log_id, $response, "users");
      die($response);
    }    
  }
  public function add_process($post, $service_id){
  $ip = $_SERVER['REMOTE_ADDR'];

  $result["code"] = 0;
  $result["message"] = "ok";

if($post["group"] == "persona")
        $result = $this->add_persona($post, $service_id);
    else if($post["group"] == "subscriber")
        $result = $this->add_subscriber($post, $service_id);        
    else return $this->group_error();
    return $result;
}
public function add_persona($post, $service_id){
 $profile = array();    
    $validate = $this->has_persona_in_service($post["name"], $service_id);
    if($validate){
        return $this->record_exists_error($post['group']);
    }
    else{
        $persona = new \TblPersonas();    
        $persona->name = $post["name"];    
        $persona->service_id = $service_id;

        if(isset($data["profile"]))
            $profile = $post["profile"];

        if(count($profile) > 0)
            $persona->profile = json_encode($profile);
        if(isset($post['additional_info']))
            $persona->additional_info = json_encode($post['additional_info']);

        $persona->date_created = gmdate("Y-m-d H:i:s");
        $persona->save();          
        return $this->transaction_success();
    }
  }
   public function add_subscriber($data, $service_id){
    $profile = array();    
    $validate = $this->has_subscriber_in_service($data["name"], $service_id);

    if($validate){
        return $this->record_exists_error($data['group']);
    }
    else{
        $subscriber = new \TblSubscribers();    
        $subscriber->name = $data["name"];    
        $subscriber->service_id = $service_id;

        if(isset($data["profile"]))
            $profile = $data["profile"];

        if(count($profile) > 0)
            $subscriber->profile = json_encode($profile);
        if(isset($data['additional_info']))
            $subscriber->additional_info = json_encode($data['additional_info']);
        
        if(isset($data["membership"]))
          $subscriber->membership_type = $this->get_membership_type($service_id, $data["membership"]);
        

        $subscriber->date_created = gmdate("Y-m-d H:i:s");
        $subscriber->save();          
        return $this->transaction_success();
    }
  }

   public function update_process($post){
    $result["code"] = 0;
    $result["message"] = "ok";
    $service = \TblServices::where('code', $post['service_code'])->first();
    
    if($post["group"] == "persona")
        $result = $this->update_persona($post, $service->id);
    else if($post["group"] == "subscriber")
        $result = $this->update_subscriber($post, $service->id);        
    else return $this->group_error();    
    return $result;
  }

   protected function update_persona($data, $service_id){
    $profile = array();
    $validate = $this->has_persona_in_service($data["name"], $service_id);
    if(!$validate){
        return $this->record_not_found_error($data['group']);
    }
    else{    
        $model = new TblPersonas();
        $persona = \TblPersonas::get_persona(array("service_id" => $service_id, "name" => $data["name"]));   
        if(isset($data["status"])){
          if(!$this->validate_status($data["status"])){
            $result["code"] = "511";
            $result["message"] = "Invalid status";
            return $result;            
          }
          else $persona->status = $data["status"];
        }
        
        if(isset($data["profile"]))
            $profile = $data["profile"];

        if(count($profile) > 0)
            $persona->profile = json_encode($profile);
        if(isset($data['additional_info']))
            $persona->additional_info = json_encode($data['additional_info']);

        $persona->save();    
        return $this->transaction_success();
    }
  }

   protected function update_subscriber($data, $service_id){
    $profile = array();
    $validate = $this->has_subscriber_in_service($data["name"], $service_id);
    if(!$validate){
        return $this->record_not_found_error($data['group']);
    }
    else{
        $subscriber = \TblSubscribers::get_subscriber(array("service_id" => $service_id, "name" => $data["name"]));
        if(isset($data["status"])){
          if(!$this->validate_status($data["status"])){
            $result["code"] = "511";
            $result["message"] = "Invalid status";
            return $result;            
          }
          else $subscriber->status = $data["status"];
        }

        if(isset($data["profile"]))
            $profile = $data["profile"];        
        if(count($profile) > 0)
            $subscriber->profile = json_encode($profile);
        if(isset($data['additional_info']))
            $subscriber->additional_info = json_encode($data['additional_info']);

        if(isset($data["membership"]))
          $subscriber->membership_type = $this->get_membership_type($service_id, $data["membership"]);        
        
        $subscriber->save();    
        return $this->transaction_success();
    }
  }  
  
  protected function get_membership_type($service_id, $membership){
    $status = "normal";
    $service = \TblServices::where('id', $service_id)->first();
    if($service->has_membership){
      if($membership == "billed")
        $status = "billed";
      else $status = "free";      
    }
    else $status = "normal";
    
    return $status;
  }
  
  protected function group_error(){
      $result["code"] = "510";      
      $result["message"] = "Invalid group";      
      return $result;
  }  
  
  protected function transaction_success(){
      $result["code"] = "0";      
      $result["message"] = "ok";      
      return $result;
  }  
  
  protected function record_exists_error($group){
      $result["code"] = "507";      
      $result["message"] = ucfirst($group)." already exists in service";      
      return $result;
  }  
  
  protected function record_not_found_error($group){
      $result["code"] = "506";      
      $result["message"] = ucfirst($group)." not found in service";      
      return $result;
  }  
  
  protected function has_persona_in_service($name, $service_id){
    $persona = \TblPersonas::get_persona(array("service_id" => $service_id, "name" => $name));
    if($persona->count() > 0)
        return true;
    else return false;    
  }
  
  protected function has_subscriber_in_service($name, $service_id){
    $subscriber = \TblSubscribers::get_subscriber(array("service_id" => $service_id, "name" => $name));
    if($subscriber->count() > 0)
        return true;
    else return false;    
  }
  
  protected function validate_status($status){
    $set = array("active", "inactive", "delete");
    if(in_array($status, $set))
     return true;
    else return false;
  }   

  protected function curl_to_v2($action){
    if (!isset($_POST["data"])) {
      $this->log_id = \ApiHelper::log_inbound_request($_POST, url('/'), $ip);
      \ApiHelper::format_error($this->log_id, "message");
    }

    $response = \UtilHelper::send_curl(\Config::get('api.im2_users_url') . $action, $_POST["data"]);
    die($response);
  }

}