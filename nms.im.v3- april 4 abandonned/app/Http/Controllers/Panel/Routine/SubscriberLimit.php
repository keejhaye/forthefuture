<?php

namespace App\Http\Controllers\Panel\Routine;

use Illuminate\Http\Request;
use DateTime;
use DateTimezone;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class SubscriberLimit extends Controller
{
  public function reset_messages_counter() {
    \Benchmark::start("reset_subscriber_messages_counter");
    $date = gmdate("Y-m-d H:i:s");
    $count = \TblSubscriberMessageLimit::reset_expired_subscriber_message_limit($date, "day");
    \Benchmark::stop("reset_subscriber_messages_counter");

    $elapsed = \Benchmark::get("reset_subscriber_messages_counter");

    $total_time["time"] = $elapsed["time"];
    $total_time["memory"] = $elapsed["memory"];

    \LogHelper::update_routine_execution("reset subscriber messages limit", $elapsed["time"], "day");
    echo '[' . $elapsed["time"] . " | " . $elapsed["memory"] . '] ' . $count . ' records have been reset.';
  }
  
  public function reset_messages_counter_monthly() {
    \Benchmark::start("reset_subscriber_messages_counter_monthly");
    $date = gmdate("Y-m-d");
    $count = \TblSubscriberMessageLimit::reset_expired_subscriber_message_limit($date, "month");
    \Benchmark::stop("reset_subscriber_messages_counter");

    $elapsed = \Benchmark::get("reset_subscriber_messages_counter_monthly");

    $total_time["time"] = $elapsed["time"];
    $total_time["memory"] = $elapsed["memory"];

    \LogHelper::update_routine_execution("reset subscriber messages limit monthly", $elapsed["time"], "month");
    echo '[' . $elapsed["time"] . " | " . $elapsed["memory"] . '] ' . $count . ' records have been reset.';
  }

  public function reset_personas_counter() {
    \Benchmark::start("reset_subscriber_personas_counter");
    $date = gmdate("Y-m-d");
    $count = \TblSubscriberPersonaLimit::reset_expired_subscriber_persona_limit($date, "day");
    \Benchmark::stop("reset_subscriber_personas_counter");

    $elapsed = \Benchmark::get("reset_subscriber_personas_counter");

    $total_time["time"] = $elapsed["time"];
    $total_time["memory"] = $elapsed["memory"];

    \LogHelper::update_routine_execution("reset subscriber personas limit", $elapsed["time"], "day");
    echo '[' . $elapsed["time"] . " | " . $elapsed["memory"] . '] ' . $count . ' records have been reset.';
  }
  
  public function reset_personas_counter_monthly() {
    \Benchmark::start("reset_subscriber_personas_counter_monthly");
    $date = gmdate("Y-m-d");
    $count = \TblSubscriberPersonaLimit::reset_expired_subscriber_persona_limit($date, "month");
    \Benchmark::stop("reset_subscriber_personas_counter_monthly");

    $elapsed = \Benchmark::get("reset_subscriber_personas_counter_monthly");

    $total_time["time"] = $elapsed["time"];
    $total_time["memory"] = $elapsed["memory"];

    \LogHelper::update_routine_execution("reset subscriber personas limit monthly", $elapsed["time"], "month");
    echo '[' . $elapsed["time"] . " | " . $elapsed["memory"] . '] ' . $count . ' records have been reset.';
  }
}