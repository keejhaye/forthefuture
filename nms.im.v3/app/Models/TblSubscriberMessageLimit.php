<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TblSubscriberMessageLimit extends Model {

  public $timestamps = false;

  protected $table = 'tbl_subscriber_message_limit';
  protected $fillable = ['subscriber_id', 'messages_count', 'reset_on', 'date_created', 'messages_count_monthly', 'monthly_reset_on'];


  public function tblSubscribers() {
    return $this->belongsTo(\App\Models\TblSubscribers::class, 'subscriber_id', 'id');
  }

  public static function reset_expired_subscriber_message_limit($reset_date, $period){
    $limit = 1000;
    $cnt = 0;
    $ids = array();

    if($period == "day"){
      $filter_date = "reset_on";
      $filter_count = "messages_count";
      $in_seconds = 86400;
    }
    else{
      $filter_date = "monthly_reset_on";
      $filter_count = "messages_count_monthly";
      $in_seconds = 2592000;
    }

    $results = \TblSubscriberMessageLimit::where($filter_date, '<=', $reset_date)
                  ->where($filter_date, '!=', '000-00-00 00:00:00')
                  ->where('messages_count', '>', 0)
                  ->limit($limit)
                  ->get();

    if($results->count() > 0){
      foreach($results as $key){
        $ids[] = $key->id;
        $cnt++;
      }
    }
    
    $next_reset = date("Y-m-d H:i:s", strtotime($reset_date) + $in_seconds);
    if(count($ids) > 0){  
      $query = \TblSubscriberMessageLimit::whereIn('id', $ids)
                  ->update(array(
                      $filter_count => 0,
                      $filter_date => $next_reset,
                    ));
    }
    
    return $cnt;
  }
}
