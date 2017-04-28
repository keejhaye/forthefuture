<?php
namespace App\Helpers;

class UserHelper
{
	static function get_operator_stat_range(){
		$range = array(
		    "start" => \DateTimeHelper::get_date(\Config::get('application.date_format_php'), gmdate('Y-m-d 00:00:00'), \Config::get('application.operator_stats_timezone')),
		    "end" => \DateTimeHelper::get_date(\Config::get('application.date_format_php'), gmdate('Y-m-d 23:59:59'), \Config::get('application.operator_stats_timezone')),
		);

		return $range;
	}
}