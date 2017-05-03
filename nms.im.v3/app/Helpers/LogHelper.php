<?php
namespace App\Helpers;

class LogHelper
{
	
	protected static $_timer_started = false;
	protected static $_timer_start_time = null;
	protected static $_trace_enabled = false;

	static function log_user_activity_modified($status = null, $remarks = null, $id = null) {
		if($id == null)
			$id = session('user.id');

	    $model = new \TblUserSystemLogs();
	    $log = $model->get_active_user_log($id);

	    if ($log != null) {
			if ($log->status != $status) {
		        $log->end_time = date(config("application.date_format_php"));
		        $log->active = 1;
		        $log->RptUserSystemLogs->first()->end_time = date(config("application.date_format_php"));
		        $log->RptUserSystemLogs->first()->active = 1;
		        $log->save();
	     	}

			if ($log->status != $status) {
	        	$model->add_log($id, $status, $remarks);
	    	}
	    } else {
	    	$model->add_log($id, $status, $remarks);
	    }
	}

	static function log_user_activity($status = null, $id = null) {
		if($id == null)
	    	$id = session('user.id');

	    $model = new \TblUserLogs();
	    $log = $model->get_active_user_log($id);

	    if ($log != null) {
			if ($log->status != $status) {
		        $log->end_time = gmdate("Y-m-d H:i:s");
		        $log->active = 0;
		        $log->RptUserLogs->first()->end_time = date(config("application.date_format_php"));
		        $log->RptUserLogs->first()->active = 0;
		        $log->save();
	    	}
	    	if ($status != "logout" && $log->status != $status) {
	        	$model->add_log($id, $status);
	    	}
	    } else {
	    	if ($status != "logout")
	        	$model->add_log($id, $status);
	    }
	}

	/**
	* set an array to a session variable
	* or update an existing variable with a new array value
	*/
	static function log_array_to_session($session_name, $value, $multiple_logs = false){
		if (!empty($new )) {
			$new = \Session::get($session_name);      
			if(!$multiple_logs){
				$new[] = $value;
			}
			else{
				if(count($value) > 0)
				{
					foreach($value as $key){
						$new[] = $key;
					}
				}            
			}

			\Session::set($session_name, $new);  
		 }  
	}

	static function logfile($dump, $name, $directory, $separate_per_date = true) {
		// CHANGETHIS
		$logs_directory = "$directory/";
		if (!is_writable($logs_directory))
			return false;

		if (file_exists($logs_directory) == false)
			return;

		$dump = ltrim($dump, "\n");
		$dump = str_replace("\n", "\n\t", $dump);

		if ($separate_per_date) {
			$time_stamp = date_time::get_date("H:i:s", null, +8.0);
			$date_file = date_time::get_date("Y-m-d", null, +8.0);
			$file = "{$logs_directory}{$name}-{$date_file}.txt";
		} 
		else {
			$time_stamp = date_time::get_date("Y-m-d H:i:s", null, +8.0);
			$file = "{$logs_directory}{$name}.txt";
		}

		$mem = round(memory_get_usage() / 1024 / 1024) . 'M';
		$time_stamp .= '|' . $mem;

		$dump = "[{$time_stamp}]\t{$dump}";

		$file_data = "$dump\n";

		file_put_contents($file, $file_data, FILE_APPEND | LOCK_EX);
		return true;
	}

  static function enable_tracing() {
    self::$_trace_enabled = true;
  }

  static function update_routine_execution($name, $span, $frequency = "") {
    $date = gmdate("Y-m-d H:i:s");
    $routine = \UtlRoutine::firstOrNew(['name' => $name]);

    if($routine->id != NULL) {
      $routine->last_execution = $date;
      $routine->timespan = $span;
    }
    else{
      $routine->last_execution = $date;
      $routine->date_created = $date;
      $routine->frequency = "/".$frequency;
      $routine->timespan = $span;
      $routine->status = 'active';
    }

    $routine->save();
  }

  static function user_activity($section, $record, $action, $old_data = "-", $new_data = "-", $remarks = null){
    $params["user_id"] = session('user.id');
    $params["section"] = $section;
    $params["record_changed"] = $record;
    $params["old_data"] = $old_data;
    $params["new_data"] = $new_data;
    $data = array_merge($params, self::user_activity_log_action($action, $old_data, $new_data));
    
    if($remarks != null)         
      $data["remarks"] = $remarks;
    
    if($data["changed_fields"] != "")
      self::add_user_activity_log($data);    
  }

   protected static function user_activity_log_action($action, $old_data = "-", $new_data = "-"){    
    $params = array();
    if($action == "add"){
      $params["changed_fields"] = "(added)";
      $params["is_new"] = 1;
    }
    else if($action == "update"){
      $fields = self::get_updated_fields($old_data, $new_data);
      $params["changed_fields"] = $fields;  
      $params["is_new"] = 0;
    }
    else if($action == "delete"){
      $params["changed_fields"] = "(deleted)";  
      $params["is_new"] = 1;      
    }
    else if($action == "unassign"){
      $params["changed_fields"] = "";
      $params["is_new"] = 0;
    }
    return $params;
  }

    protected static function get_updated_fields($old_data, $new_data){
    $fields = "";
    foreach($old_data as $key => $value){
      if($value != $new_data[$key])
        $fields .= str_replace("_", " ", $key).", ";
    }
    $fields = rtrim($fields);
    $fields = rtrim($fields, ",");    
    
    return $fields;
  }
  
  protected static function add_user_activity_log($params){
    $record = new \TblUserActivities();
    $record->user_id = $params["user_id"];
    $record->old_data = json_encode($params["old_data"]);
    $record->new_data = json_encode($params["new_data"]);
    $record->section = $params["section"];
    $record->changed_fields = $params["changed_fields"];
    $record->is_new = $params["is_new"];
    $record->record_changed = $params["record_changed"];
    if(isset($params["remarks"]) && trim($params["remarks"]) != "")
      $record->remarks = $params["remarks"];
    $record->date_created = gmdate("Y-m-d H:i:s");
    $record->save();
  }
  

}