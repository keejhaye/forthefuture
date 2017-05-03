<?php

namespace App\Http\Controllers\Panel\Routine;

use Illuminate\Http\Request;
use DateTime;
use DateTimezone;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class Statistics extends Controller
{
  public function reset() {
    $datetime = new DateTime();
    $converted_time = new DateTimeZone('Australia/Melbourne');
    $datetime->setTimezone($converted_time);
    $time_now = $datetime->format('Y-m-d H:i:s');
    $now = strtotime($time_now);
    $mid = strtotime($datetime->format('Y-m-d 23:59:59'));
    $reset_executed = $mid - 5;

    // if($now > time() + 86400){
    if($now >= $mid){ // WILL CHECK IF CURRENT TIME IS 23:59:59
      $utl_statistics = \UtlStatistics::get_stats('all_statistics');
      if($utl_statistics == null) die('no');
      if($utl_statistics->executed == 1) die('no');

      $assigned_conversations = \Redis::keys('assigned_conversations_*');
      $unassigned_conversations = \Redis::keys('unassigned_conversations_*');
      $total_inbound = \Redis::keys('total_inbound_*');
      $total_outbound = \Redis::keys('total_outbound_*');

      foreach($assigned_conversations as $key){
          \Redis::set($key, 0);
      }
      foreach($unassigned_conversations as $key){
          \Redis::set($key, 0);
      }
      foreach($total_inbound as $key){
          \Redis::set($key, 0);
      }
      foreach($total_outbound as $key){
          \Redis::set($key, 0);
      }

      \Redis::set('assigned_conversations', 0);
      \Redis::set('unassigned_conversations', 0);
      \Redis::set('online_count', 0);
      \Redis::set('total_inbound', 0);
      \Redis::set('total_outbound', 0);

      $utl_statistics->time_executed = $time_now;
      $utl_statistics->executed = 1;
      $utl_statistics->save();

      echo 'Statistics has been reset';
    }
    else{
      if($now >= $reset_executed){
        \UtlStatistics::where('name','all_statistics')->update(['executed' => 0]);
      }
      
      die('no');
    }
  }

  public function reset_user_stats(){
    $datetime = new DateTime();
    $converted_time = new DateTimeZone('Asia/Magadan');
    $datetime->setTimezone($converted_time);
    $time_now = $datetime->format('Y-m-d H:i:s');
    $now = strtotime($time_now);
    $new_data = array("outbound_count" => 0,"chat_time" => "00:00:00");

    if($now > time() + 86400){
      $keys = \Redis::keys('user_stat:*');

      foreach($keys as $key){
          \Redis::set($key, json_encode($new_data));
      }

      echo 'User stats has been reset';
    }
    else{
      die('no');
    }
  }

  public function current(){
    $date = [
      'start' => \DateTimeHelper::get_date('Y-m-d H:i:s', gmdate('Y-m-d 00:00:00'), '11'),
      'end' => \DateTimeHelper::get_date('Y-m-d H:i:s', gmdate('Y-m-d 23:59:59'), '11')
    ];

    $total = "SELECT 
              (SELECT COUNT(id) FROM rpt_messages WHERE bound_time BETWEEN '{$date['start']}' AND '{$date['end']}' AND direction = 'inbound') AS inbound,
              (SELECT COUNT(id) FROM rpt_messages WHERE bound_time BETWEEN '{$date['start']}' AND '{$date['end']}' AND direction = 'outbound') AS outbound";

    $result = \DB::select(\DB::raw($total));

    \Redis::set('total_inbound', $result[0]->inbound);
    \Redis::set('total_outbound', $result[0]->outbound);
    die(print_r($result,1));
  }
}