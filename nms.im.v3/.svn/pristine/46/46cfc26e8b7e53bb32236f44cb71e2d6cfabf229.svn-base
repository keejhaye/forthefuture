<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class UtlInboundRequestLimiter extends Model {

    public $timestamps = false;
    protected $table = 'utl_inbound_request_limiter';
    protected $fillable = ['ip_address', 'type', 'requests', 'reset_on'];

    public static function reached_limit($ip) {
        if (empty($limit)) {
            $limit = Config::get('api.request_limit');
        }
        $row = \UtlInboundRequestLimiter::where('ip_address', $ip)->first();

        if ($row == null) {
            return false;
        }

        if ($row->requests <= $limit) {
            return false;
        } else {
            return true;
        }
    }

  public static function increase($ip, $modifier = null){
    $row = \UtlInboundRequestLimiter::where('ip_address', $ip)->first();

    if($modifier == null)
      $modifier = 1;

    if($row == null){
      $row = new \UtlInboundRequestLimiter();
      $row->ip_address = $ip;
      $row->reset_on = date('Y-m-d H:i:s',
              strtotime('+'.Config::get('api.request_timeout').'SECONDS'));
      $row->requests = 0;
    }

    $row->requests += $modifier;
    $row->save();

    return $row;
  }

  public static function reset(){
    $rows = \UtlInboundRequestLimiter::where('reset_on', '<=', gmdate('Y-m-d H:i:s'))->get();

    if($rows->count() > 0){
      foreach($rows as $row){
        // die(var_dump($row->toArray()));
        $row->reset_on = gmdate('Y-m-d H:i:s',
              strtotime('+'.Config::get('api.request_timeout').'SECONDS'));
        $row->requests = 0;
        $row->save();
      }

    }

    return $rows;
  }

}
