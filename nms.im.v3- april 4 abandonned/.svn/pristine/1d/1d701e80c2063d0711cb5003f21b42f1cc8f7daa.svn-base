<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RptMessages extends Model {

  public $timestamps = false;

  protected $table = 'rpt_messages';
  protected $fillable = ['conversation_id', 'message_id', 'persona_id', 'persona_name', 'subscriber_id', 'subscriber_name', 'service_id', 'service_name', 'status', 'bound_time', 'direction', 'assigned_time', 'duration', 'user_id', 'fullname', 'date_created', 'fetched', 'is_billed'];

	static function count_outbound($user_id){
		$range = \UserHelper::get_operator_stat_range();

		return \RptMessages::
		        select(\DB::raw('COUNT(id) AS count'))
		        ->where('user_id','=', $user_id)
		        ->where('direction','=','outbound')
		        ->whereBetween('bound_time', [$range['start'], $range['end']])
		        ->get()->toArray();
	}

  static function operator_avg_response($user_id){
    $range = \UserHelper::get_operator_stat_range();

    return \RptMessages::
            select(\DB::raw('AVG(duration) as total_duration'))
            ->where('user_id','=',$user_id)
            ->where('direction','=','outbound')
            ->whereBetween('bound_time',[$range['start'], $range['end']])
            ->get()->toArray();

  }

}
