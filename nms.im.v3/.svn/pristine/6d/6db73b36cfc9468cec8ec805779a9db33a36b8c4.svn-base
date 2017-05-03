<?php

namespace App\Helpers;
use App\Models\UtlInboundRequests;
class ApiHelper {

    static function request_limit_error($type = null) {
        $response["authentication"]["code"] = "500";
        $response["authentication"]["message"] = "Limit Reached";
        $result = json_encode($response);
        die($result);
    }

    static function format_error($log_id, $type = null) {
        $result["authentication"]["code"] = "502";
        $result["authentication"]["message"] = "Invalid format. Check the API documentation.";
        $response = json_encode($result);
        self::save_inbound_response($log_id, $response, $type);
        die($response);
    }

    static function log_inbound_request($post, $url, $ip_address, $service_code = null, $reference = null) {
        $model = new \UtlInboundRequests();
        $model->url = $url;
        $model->request = str_replace("/", "",$post);
        $model->ip_address = $ip_address;
        $model->date_created = date("Y-m-d H:i:s");

        if ($service_code != null)
            $model->service_code = $service_code;
        else
            $model->service_code = 0;

        if ($reference != null)
            $model->reference = $reference;

        $model->save();

        return $model->id;
    }

    static function validate_json_format($str, $log_id, $type = null) {
        if (!self::is_json($str)) {
            $response["authentication"]["code"] = '503';
            $response["authentication"]["message"] = 'Data sent is not a valid json string';
            self::save_inbound_response($log_id, $response, $type);
            die(json_encode($response));
        }
    }

    static function is_json($data) {
        if (json_encode($data) != null)

            return true;
        else
            return false;
    }

    static function save_inbound_response($log_id, $response, $type, $failed = 1, $service_code = 0) {
        \Benchmark::stop("api");
        $log = \UtlInboundRequests::where('id', $log_id)->first();
        $log->response = json_encode($response);
        $log->type = $type;
        $log->failed = $failed;
        $log->service_code = $service_code;
        $info = \Benchmark::get("api");
        if ($info)
          $log->execution_info = json_encode($info);
        $log->save();
    }

    static function sanitize_data($raw_data, $level = 0) {
        // not a valid array or empty
       
        if (!is_array($raw_data) || empty($raw_data)) {
            return null;
        }

        $sanitized = array();

        foreach ($raw_data as $key => $value) {
            $sanitize_key = \UtilHelper::sanitize($key, $level);

            if (is_array($value))
                $sanitized_value = self::sanitize_data($value, $level);
            else {
                $sanitized_value = \UtilHelper::sanitize($value, $level);
            }

            $sanitized[$sanitize_key] = $sanitized_value;
        }

        return $sanitized;
    }
     static function validate_parameters($data, $required_parameters = array()) {
    $result["authentication"]["code"] = 0;
    if (count($required_parameters) > 0) {
      $emp = "";
      foreach ($required_parameters as $key => $value) {
        if (isset($data[$value])) {
          $tmp = trim($data[$value]);
          if ($tmp == "")
            $emp .= $value . ", ";
        }
        else
          $emp .= $value . ", ";
      }
      $emp = rtrim($emp, ", ");

      if ($emp != "") {
        $result["authentication"]["code"] = "504";
        $result["authentication"]["message"] = "One or more of the required parameters have not been specified: {$emp}";
      }
    }

    return $result;
  }
  static function check_credentials($username, $password, $service, $log_id, $ip, $type = null) {

    if (!$service) {
      $response = array();
      $response["authentication"]["code"] = '505';
      $response["authentication"]["message"] = 'Service does not exists';
      $result = json_encode($response);
      self::save_inbound_response($log_id, $result, $type);
      die($result);
    }
    if ($service->enable_whitelist == 1) {
   
      $whitelist = \TblWhitelist::find_by_service($service->id, $ip);

      if (count($whitelist) == 0)
        return false;

      return self::verify_credentials($service, $username, $password);
    }
    else{
      return self::verify_credentials($service, $username, $password);
    }
  }
     static protected function verify_credentials($service, $usermame, $password) {
    if ($service->aggregator_username != $usermame)
      return false;
    else if ($service->aggregator_password != $password)
      return false;
    else
      return true;
  }
  
    static function authentication_error($log_id, $ip, $type = null) {
    $response["authentication"]["code"] = '501';
    $response["authentication"]["message"] = "Authentication Failure for IP: {$ip}";
    $result = json_encode($response);
    self::save_inbound_response($log_id, $result, $type);
    die($result);
  }
    static function authentication_success() {
    $response["authentication"]["code"] = 0;
    $response["authentication"]["message"] = "ok";
    return $response;
  }
  
  static function verify_subscriber($log_id, $type = null) {
    $response["transaction"]["code"] = '';
    $response["transaction"]["message"] = "Subscriber does not exist";
    $result = json_encode($response);
    self::save_inbound_response($log_id, $result, $type);
    die($result);
  }

  static function verify_persona($log_id, $type = null) {
    $response["transaction"]["code"] = '';
    $response["transaction"]["message"] = "Persona does not exist";
    $result = json_encode($response);
    self::save_inbound_response($log_id, $result, $type);
    die($result);
  }


}
