<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class TblAggregatorResponses extends Model {

    public $timestamps = false;

    protected $table = 'tbl_aggregator_responses';
    protected $fillable = ['service_id', 'message_id', 'status', 'target_response', 'target_url', 'meta', 'last_attempt', 'attempts', 'execution', 'executed', 'timeout', 'date_created'];
    
	protected $aged_locks       = 120;    // 120 seconds or 2 minutes to age locks
	protected $response_limit   = 50;     // limit to 20 at a time ( this is a cross-site routine )
	protected $response_timeout = 10;     // how many seconds until we timeout response ( this is to limit cross-site spikes )
	protected $max_attempts     = 20;     // how many attempts before we give up on this?
	protected $max_limit        = 10000;     // how many attempts before we give up on this?

	/**
	* 
	* @var assoc
	*/
	static $_2173_summary;
	static $sent_via_rolling_curl = 0;

    public function TblServices() {
        return $this->belongsTo(\App\Models\TblServices::class, 'service_id', 'id');
    }

    public function TblMessages() {
        return $this->belongsTo(\App\Models\TblMessages::class, 'message_id', 'id');
    }

	static function _2173_pending_aggregator_responses($model, $service_id, $group = false){
		$datetime_end = \DateTimeHelper::format_datetime(null)->format("Y-m-d H:i:s");

		$max["the_max"] = 0;
		$query = \TblAggregatorResponses
		    ::where("execution",'<=', $datetime_end)
		    ->Where("status", "pending")
		    ->Where("attempts",'<', $model->max_attempts)
		    ->Where("id",'>', $max["the_max"])
		    ->orderBy("execution")
		    ->take($model->response_limit);

		if(!$group)
			$query->where("service_id", $service_id);
		else 
			$query->whereIn("service_id", $service_id);

		$results = $query->get();
		return $results;
	}

	static function send_pending_2173($service_id, $group = false){
		\TracesHelper::add("2173 aggregator response unit of work, begin");
		$model = new \TblAggregatorResponses();

		\TracesHelper::add('fetching '. $model->response_limit . ' per aggregator...');
		$pending = \TblAggregatorResponses::_2173_pending_aggregator_responses($model, $service_id, $group);
		$count = $pending->count();

		if($count == 0){
			\TracesHelper::add('no pending found. wrapping up.');
			return false;
		}

		\TracesHelper::add("found ".$count." pending.");

		// send the pending
		\TracesHelper::add('sending...');

		$results = \TblAggregatorResponses::_2173_send_pending($pending);
		//\LogHelper::logfile('sent: '.print_r($results, true), 'outbound', 'debug');

		return array("summary" => \TblAggregatorResponses::$_2173_summary, 
		     "sent" => \TblAggregatorResponses::$sent_via_rolling_curl,
		     "total" => $count);
	}  

	static function _2173_send_pending($pending){
		\TracesHelper::add("begin curl");

		$rc = new \RollingCurl\RollingCurl();

		$rc->setOptions(array(
			CURLOPT_CONNECTTIMEOUT => \Config::get("curl.connection_timeout"),
			CURLOPT_TIMEOUT => \Config::get("curl.timeout")
		));
		$rc->setSimultaneousLimit(\Config::get("curl.window_size"));
	    $rc->setCallback(function(\RollingCurl\Request $request, \RollingCurl\RollingCurl $rollingCurl){
	    	self::_2173_send_pending_callback($request);
	    });

		foreach($pending as $request){
			$rq = new \RollingCurl\Request($request->target_url);
			$rq->_obj = $request;
			$rq->setMethod("POST");
			$rq->setPostData(array("data"=> $request->meta));
			$rq->addOptions(array(
			                CURLOPT_SSL_VERIFYPEER => 0,
			                CURLOPT_SSL_VERIFYHOST => 0,
			                CURLOPT_POST => 1
			                ));

			$rc->add($rq);
		}    
		$rc->execute();
	}

	static function _2173_send_pending_callback($request){
		$info = $request->getResponseInfo();
		$response = $request->getResponseText();
		\LogHelper::enable_tracing();
		$time = \DateTimeHelper::format_datetime(null)->format("Y-m-d H:i:s");
		$request->_obj->last_attempt = $time;
		$request->_obj->attempts++;
		$request->_obj->timeout = $info["total_time"];
		$request->_obj->target_response = $response;
		$request->_obj->target_response = "'".\UtilHelper::clean_text($response)."' H{$info["http_code"]}";
		\TracesHelper::add("curl ".$request->_obj->id." complete({$info["http_code"]}): "
		  .$response);
		if(($info["http_code"] == "200" ||
		   $info["http_code"] == "100") &&
		        self::_verify_response_st($info["http_code"], $response)){

		  self::$_2173_summary[$request->_obj->id]["status"] = "success";
		  self::$sent_via_rolling_curl++;

		  $request->_obj->status = "done";
		  $request->_obj->executed = $time;
		     if(\Config::get('curl.enable_send_to_loopsnoop')){
        \UtilHelper::send_curl('http://10.42.74.68:3000/command/im3/outbound_message', $request->_obj->meta);
      }
		}
		else{
		  $minutes = $request->_obj->attempts;

		  $retry = \DateTimeHelper::format_datetime(null)
		        ->add(new \DateInterval("PT{$minutes}M"))->format("Y-m-d H:i:s");
		        
		  $request->_obj->execution = $retry;
		}

		$request->_obj->save();
	}

	static function _verify_response_st($header, $response){
		$is_valid_header = false;
		$is_valid_response = false;

		$is_valid = false;

		if($header == 200 || $header == 100){
		  $is_valid_header = true;
		}

		$is_valid_response = true; // all true for now, uncomment above code otherwise

		$is_valid = $is_valid_header && $is_valid_response;

		return $is_valid;
	}  

	static function execute_aggregator_responses_with_ssl($service_id, $group = false){
		$datetime_end = \DateTimeHelper::format_datetime(null)->format("Y-m-d H:i:s");
		$max["the_max"] = 0;

		$query = \TblAggregatorResponses
		    ::where("execution",'<=', $datetime_end)
		    ->Where("status", "pending")
		    ->Where("attempts",'<', $model->max_attempts)
		    ->Where("id",'>', $max["the_max"])
		    ->orderBy("execution")
		    ->take($model->response_limit);

		if(!$group)
			$query->where("service_id", $service_id);
		else 
			$query->whereIn("service_id", $service_id);

		$results = $query->get();
		return $results;

		if($results->count() > 0){
			foreach($results as $aggr_response){
				self::try_trigger_response($aggr_response);
			}
		}
	}

	protected static function try_trigger_response($response_object){
          try {
              
                  	 $response_obj = self::trigger_response($response_obj);
              
          } catch (\Exception $e) {
   				echo $e;
          }

          return $response_obj;
	}
	  protected static function trigger_response($response_obj){
    $listener_url = $response_obj->target_url;    
    $data         = $response_obj->meta;
    $url = 'http://im.nmsloop.com/ssl_sender.php';  
    $info = array(
        "url" => $listener_url,
        "data" => json_decode($data, true),
    );  
    $info = json_encode($info);
    $post_array = "data=".urlencode($info);
       
    $date_now = gmdate("Y-m-d H:i:s");
    if(function_exists("curl_init")){
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);      
      curl_setopt($ch, CURLOPT_HEADER, 0);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_TIMEOUT, 10);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $post_array);
      curl_setopt($ch, CURLOPT_POST, 1);
      $target_response = curl_exec($ch);
      curl_close($ch);
    }else{
      $target_response = "curl not enabled on this server.";
    }

    $response_obj->target_response = $target_response;
    $response_obj->last_attempt = $date_now;
    $response_obj->attempts ++;
    
    if($target_response === false){
      $response_obj->execution = $date_now; // failed, run again later
    }else{
      $response_obj->executed = $date_now;
      $response_obj->status = "done";
    }

    $response_obj->save();

    return $response_obj;
  }  
}
