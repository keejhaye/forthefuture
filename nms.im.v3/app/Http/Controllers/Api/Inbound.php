<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Inbound extends Controller {

    public function index(){
     
    }

    public function message(Request $request) {
        // if(\Config::get('api.send_request_to_v2')){
        //     $this->curl_message_to_v2();
        // }

        //clock()->startEvent('message', 'Chat main page.');
        $ip = $_SERVER['REMOTE_ADDR'];
        // die(var_dump($ip));
        \Benchmark::start("api");
        if (\UtlInboundRequestLimiter::reached_limit($ip)) {
            \ApiHelper::request_limit_error("message");
        }
        
        // increase or create record after limit checking
        \UtlInboundRequestLimiter::increase($ip);
        
        if (!isset($_POST["data"])) {
            $this->log_id = \ApiHelper::log_inbound_request($_POST, url('/'), $ip);
            \ApiHelper::format_error($this->log_id, "message");
        }

        $post_str = $_POST["data"];

        $this->log_id = \ApiHelper::log_inbound_request($post_str, url('/'), $ip);
        \ApiHelper::validate_json_format($post_str, $this->log_id, "message");
        $post = json_decode($post_str, true);
        //$post = \ApiHelper::sanitize_data(json_encode($post_str, true));
        $validation = \ApiHelper::validate_parameters($post, \Config::get('api.message_parameters'));

        if ($validation["authentication"]["code"] == 0) {
            $service = \TblServices::where('code', $post['service_code'])->first();
            $creds = \ApiHelper::check_credentials($post["username"], $post["password"], $service, $this->log_id, $ip, "message");

            if (!$creds) {
                \ApiHelper::authentication_error($this->log_id, $ip, "message");
            } 
            else {
                $result = \ApiHelper::authentication_success();
                $receive = $this->receive_process($post, $service, $request);
                $result['transaction'] = $receive;
                $response = json_encode($result);
                \ApiHelper::save_inbound_response($this->log_id, $response, "message");
                echo $response;
            }
        } 
        else {
            $response = json_encode($validation);
            \ApiHelper::save_inbound_response($this->log_id, $response, "message", 1, $post["service_code"]);
            echo $response;
        }
        //clock()->endEvent('message');
    }

    public function receive_process($post, $service, $request) {
        $ip = $_SERVER['REMOTE_ADDR'];
        $from = \TblSubscribers::where("name", $post["from"])
                                ->where("service_id",$service->id)->first();
        if ($service->allow_anonymous_subscriber) {
            if (!$from) {
                $from = $this->add_subscriber($post['from'], $service->id);
            } 
            else {
                if (!$from) {
                    return $this->record_error("subscriber");
                }
            }
        }
        $to = \TblPersonas::where('name', $post["to"])->first();
        if (!$to) {
            return $this->record_error("persona");
        }
        $post_str = $_POST["data"];
        if (empty($from)) {
            $this->log_id = \ApiHelper::log_inbound_request($post_str, $request->url(), $ip);
            \ApiHelper::verify_subscriber($this->log_id, $ip, "message");    
        }
        else if(empty($to)){
            $this->log_id = \ApiHelper::log_inbound_request($post_str, $request->url(), $ip);
            \ApiHelper::verify_persona($this->log_id, $ip, "message");
        }
        else{
        $InboundLib = new \Inbound();
        $receive = $InboundLib->receive($post, $service, $from, $to);
        if($receive["status"]){
            $result["code"] = 0;
            $result["message"] = "ok";
            return $result;
        }
        else return $receive;
        }
    }
    
    protected function record_error($type){
        $result["code"] = "506";
        $result["message"] = ucfirst($type)." not found in service";
        return $result; 
    }
    
    protected function add_subscriber($name, $service_id){
        $record = new \TblSubscribers();
        $record->name = $name;
        $record->service_id = $service_id;
        $record->date_created = gmdate("Y-m-d H:i:s");
        $record->save();
        return $record;
    }

    protected function curl_message_to_v2(){
        if (!isset($_POST["data"])) {
            $this->log_id = \ApiHelper::log_inbound_request($_POST, url('/'), $ip);
            \ApiHelper::format_error($this->log_id, "message");
        }

        $response = \UtilHelper::send_curl(\Config::get('api.im2_url'), $_POST["data"]);
        die($response);
    }
}
