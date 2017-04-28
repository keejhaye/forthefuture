<?php
namespace App\Helpers;

class DateTimeHelper
{
    static function set_timezone($tz) {
        \Cookie::queue('timezone', $tz);
        return;
    }

    /**
     * Gets timezone that was set on cookie
     * Else if its null, then returns the configured default timezone 
     * @return type
     */
    static function get_timezone() {
        $tz = \Cookie::get('timezone', config("application.default_timezone"));
        return $tz;
    }

    static function get_timezone_by_offset($offset) {
        $timezones = array(
          '-12' => 'Pacific/Kwajalein',
          '-11' => 'Pacific/Samoa',
          '-10' => 'Pacific/Honolulu',
          '-9' => 'America/Juneau',
          '-8' => 'America/Los_Angeles',
          '-7' => 'America/Denver',
          '-6' => 'America/Mexico_City',
          '-5' => 'America/New_York',
          '-4' => 'America/Caracas',
          '-3.5' => 'America/St_Johns',
          '-3' => 'America/Argentina/Buenos_Aires',
          '-2' => 'Atlantic/Azores', // no cities here so just picking an hour ahead
          '-1' => 'Atlantic/Azores',
          '0' => 'Europe/London',
          '1' => 'Europe/Paris',
          '2' => 'Europe/Helsinki',
          '3' => 'Europe/Moscow',
          '3.5' => 'Asia/Tehran',
          '4' => 'Asia/Baku',
          '4.5' => 'Asia/Kabul',
          '5' => 'Asia/Karachi',
          '5.5' => 'Asia/Calcutta',
          '6' => 'Asia/Colombo',
          '7' => 'Asia/Bangkok',
          '8' => 'Asia/Singapore',
          '9' => 'Asia/Tokyo',
          '9.5' => 'Australia/Darwin',
          '10' => 'Pacific/Guam',
          '11' => 'Asia/Magadan',
          '12' => 'Asia/Kamchatka'
        );

        foreach ($timezones as $key => $value) {
          if ($key == $offset)
            return $value;
        }

        // return GMT0 if nothing was returned
        return $timezones['0'];
    }

  /**
   * computes the duration from start time to end time
   * in seconds
   * @param datetime $start_time
   * @param datetime $end_time
   * @return int duration in seconds
   */
  static function duration($start_time, $end_time){
    $duration = strtotime($end_time) - strtotime($start_time);
    return $duration;
  }

  static function get_date($format, $timestamp, $custom_timezone = 0.0) {
    $timestamp = strtotime($timestamp);

    $date_obj = gmdate(\Config::get("application.date_format_php"));

    if ($timestamp != null) {
      $date_obj = gmdate(\Config::get("application.date_format_php"), $timestamp);
    }

    if ($custom_timezone != null) {
      $custom_timezone = -(int) $custom_timezone;

      $adjust_str = "{$date_obj} {$custom_timezone}hours";

      $date_obj = gmdate(\Config::get("application.date_format_php"), strtotime($adjust_str));
    }

    // format the date according to request
    return gmdate($format, strtotime($date_obj));
  }

  /**
   * computes the reset date and convert to GMT0 timezone
   * this reset date is for DB
   * @param string $reset_period (1 day or 1 month)
   * @param decimal $timezone
   * @param datetime $date
   * @return datetime
   */
  static function reset_date($reset_period, $timezone, $date = null, $in_seconds = false){
    //get date now
    if($date == null)
      $date = self::get_date("Y-m-d", null, $timezone);
    else $date = self::get_date("Y-m-d", $date, $timezone);

    if($in_seconds)
      $new_date = date("Y-m-d 00:00:00", (strtotime($date) + (int)$reset_period));
    else
      $new_date = date("Y-m-d 00:00:00", strtotime($reset_period, strtotime($date)));
    $reset = self::get_date("Y-m-d H:i:s", $new_date, -$timezone); //GMT 0
    
    return $reset;
  }

  static function format_datetime($date_string, $timezone_string = null){
    if(is_null($timezone_string))
      $timezone_string = date_default_timezone_get();

    $timezone = new \DateTimeZone($timezone_string);

    return new \DateTime($date_string, $timezone);
  }

  static function microtime() {
    list($usec, $sec) = explode(" ", microtime());
    return ((float) $usec + (float) $sec);
  }

  static function format_ts($time, $default = "00h 00m 00s") {
    if ($time == null)
      return $default;

    if ($time == 0)
      return "0";

    $d = intval(($time / 86400));
    $h = intval(($time / 3600) % 24);
    $m = intval(($time / 60) % 60);
    $s = intval($time % 60);

    if (!max($d, $h, $m, $s))
      return false;
    $st = '';
    if ($d > 0)
      $st .= ($d < 10 ? '0' . $d : $d) . 'd ';
    if ($d > 0 || $h > 0)
      $st .= ($h < 10 ? '0' . $h : $h) . 'h ';
    $st .= ($m < 10 ? '0' . $m : $m) . 'm ';
    $st .= ($s < 10 ? '0' . $s : $s) . 's ';

    return $st;
  }
}