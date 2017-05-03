<?php

namespace App\Http\Controllers\Panel\Routine;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class InboundQueue extends Controller
{

  public function execute_by_group(Request $request, $group_name) {
    clock()->startEvent('event_name', 'Event description.');
    $services = \Config::get("service_groups.{$group_name}");
    \Benchmark::start("execute_messages_queue_" . $group_name);
    $messages = \TblMessagesQueue::get_pending_queue(array("in_service" => $services));
    $inboundQueue = new \InboundQueue();
    $cnt = 0;
    if (count($messages) > 0) {
      foreach ($messages as $key => $message) {
        $inboundQueue->process_inbound_message($message->details, $message->inbound_time);    
        $message->delete();
        $cnt++;
      }   
    }
    \Benchmark::stop("execute_messages_queue_" . $group_name);

    $elapsed = \Benchmark::get("execute_messages_queue_" . $group_name);

    $total_time["time"] = $elapsed["time"];
    $total_time["memory"] = $elapsed["memory"];

    $summary = "inbound: {$cnt} | ({$total_time["time"]}s)";
    \LogHelper::logfile($summary, "messages-queue-" . $group_name, "traces");
//    log::update_routine_execution("execute messages queue ".$group_name, $elapsed["time"]);
    echo '[' . $elapsed["time"] . " | " . $elapsed["memory"] . '] ' . $cnt . ' messages have been processed.';
    clock()->endEvent('event_name');
  }
}