<?php

namespace App\Http\Controllers\Panel\Routine;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class AggregatorResponses extends Controller
{

  public function execute(Request $request, $service_id) {
    Benchmark::start("aggregator_response_routine");
    $results = TblAggregatorResponses::send_pending_2173($service_id);
    Benchmark::stop("aggregator_response_routine");

    $fail = $results["total"] - $results["sent"];

    $elapsed = Benchmark::get("aggregator_response_routine");
    $total_time["time"] = $elapsed["time"];
    $total_time["memory"] = $elapsed["memory"];
    $timestamp = date_time::get_date("Y-m-d H:i:s", null, "+8");

    $success = ($results["sent"] == null) ? 0 : $results["sent"];
    $summary = "success: {$success} | fail: {$fail} | ({$total_time["time"]}s)";

//    log::update_routine_execution("aggregator response routine " . $service_id, number_format($elapsed["time"], 2));
    log::logfile($summary, "aggregator-response-routine-" . $service_id, "traces");
    die("{$timestamp} ] {$summary}\n");
  }

  public function execute_by_group(Request $request, $group_name) {
    clock()->startEvent('event_name', 'Event description.');
    \Benchmark::start("aggregator_response_routine");
    $service_ids = \Config::get("service_groups." . $group_name);
    $results = \TblAggregatorResponses::send_pending_2173($service_ids, true);
    \Benchmark::stop("aggregator_response_routine");

    $fail = $results["total"] - $results["sent"];

    $elapsed = \Benchmark::get("aggregator_response_routine");
    $total_time["time"] = $elapsed["time"];
    $total_time["memory"] = $elapsed["memory"];
    $timestamp = \DateTimeHelper::get_date("Y-m-d H:i:s", null, "+8");

    $success = ($results["sent"] == null) ? 0 : $results["sent"];
    $summary = "success: {$success} | fail: {$fail} | ({$total_time["time"]}s)";

   // \LogHelper::logfile($summary, "aggregator-response-routine-" . $group_name, "traces");
    echo("{$timestamp} ] {$summary}\n");
    clock()->endEvent('event_name');
  }
  
  public function execute_by_group_with_ssl(Request $request, $group_name) {
    Benchmark::start("aggregator_response_routine_with_ssl");
    $service_ids = Kohana::config("service_groups." . $group_name);
    $has_ssl = TblServices::get_aggregators_with_ssl($service_ids);   
    
    $results = TblAggregatorResponses::execute_aggregator_responses_with_ssl($has_ssl, true);
    Benchmark::stop("aggregator_response_routine_with_ssl");

    $fail = $results["total"] - $results["sent"];

    $elapsed = Benchmark::get("aggregator_response_routine_with_ssl");
    $total_time["time"] = $elapsed["time"];
    $total_time["memory"] = $elapsed["memory"];
    $timestamp = date_time::get_date("Y-m-d H:i:s", null, "+8");

    $success = ($results["sent"] == null) ? 0 : $results["sent"];
    $summary = "success: {$success} | fail: {$fail} | ({$total_time["time"]}s)";

    log::logfile($summary, "aggregator-response-routine-with-ssl" . $group_name, "traces");
    die("{$timestamp} ] {$summary}\n");
  }
}