<?php

namespace App\Http\Controllers\Panel\Routine;

use Illuminate\Http\Request;
use DateTime;
use DateTimezone;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class RequestLimit extends Controller
{
  public function reset() {
    \Benchmark::start("reset_request_limiter");
    $reset_count = \UtlInboundRequestLimiter::reset()->count();
    \Benchmark::stop("reset_request_limiter");

    $elapsed = \Benchmark::get("reset_request_limiter");

    $total_time["time"] = $elapsed["time"];
    $total_time["memory"] = $elapsed["memory"];

    echo '[' . $total_time['time'] . ' | ' . $total_time['memory'] . '] ' . $reset_count . ' limiters have been reset.';
  }
}