<?php
namespace App\Helpers;

class TracesHelper
{
 	protected static $_timer_start_time = null;
	protected static $_trace_enabled = false;
	protected static $_timer_started = false;
	static $stacktrace = array();

	static function add($message) {
		if (self::$_trace_enabled) {
			$timestamp = \DateTimeHelper::microtime();
			$timestamp = substr($timestamp, 8, 6);
			$timestamp = number_format($timestamp, 3);

			$mem = round(memory_get_usage() / 1024 / 1024) . 'M';

			self::$stacktrace[count(self::$stacktrace) . '|' . $timestamp . '|' . $mem] = $message;
		}
	}
}