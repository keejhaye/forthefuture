<?php

namespace App\Http\Controllers\Panel\Routine;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class AutoresponseQueue extends Controller
{

	public function execute(){
		clock()->startEvent('event_name', 'Event description.');
	    \Benchmark::start("auto_response_routine");
	    $executed = \TblAutoresponseQueue::execute_pending();
	    \Benchmark::stop("aggregator_response_routine");

	    $elapsed = \Benchmark::get("auto_response_routine");
	    $total_time["time"] = $elapsed["time"];
	    $total_time["memory"] = $elapsed["memory"];
	    $timestamp = \DateTimeHelper::get_date("Y-m-d H:i:s", null, "+8");
	    $summary = "executed: {$executed} | ({$total_time["time"]}s)";

	//    log::update_routine_execution("autoresponse routine", number_format($elapsed["time"], 2));
	    \LogHelper::logfile($summary, "autoresponse-routine", "traces");
	    clock()->endEvent('event_name');
	    echo("[{$timestamp}] {$summary}");

	}

}