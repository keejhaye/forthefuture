<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TblSubscriberPersonaLimit extends Model {

    public $timestamps = false;

    protected $table = 'tbl_subscriber_persona_limit';
    protected $fillable = ['subscriber_id', 'persona_id', 'reset_on', 'date_created', 'reset_period'];


    public function tblPersonas() {
        return $this->belongsTo(\App\Models\TblPersonas::class, 'persona_id', 'id');
    }

    public function tblSubscribers() {
        return $this->belongsTo(\App\Models\TblSubscribers::class, 'subscriber_id', 'id');
    }


    public static function get_subscriber_persona_limit($subscriber_id, $period){
      $q = \TblSubscriberPersonaLimit::where('subscriber_id', $subscriber_id)
            ->where('reset_period', $period)
            ->get();

      return $q;
    }

    public static function reset_expired_subscriber_persona_limit($reset_date, $period){
      $limit = 1000;
      $cnt = 0;
      $ids = array();

      $results = \TblSubscriberPersonaLimit::where('reset_on', $reset_date)
                    ->where('reset_period', $period)
                    ->limit($limit)
                    ->get();

      if($results->count() > 0){
        foreach($results as $key){
          $ids[] = $key->id;
          $cnt++;
        }
      }
      
      if(count($ids) > 0){
        \TblSubscriberPersonaLimit::destroy($ids);        
      }
      
      return $cnt;
    }

    public static function find_subscriber_persona_record($subscriber_id, $persona_id, $period){
      $q = \TblSubscriberPersonaLimit::where('subscriber_id', $subscriber_id)
            ->where('persona_id', $persona_id)
            ->where('reset_period', $period)
            ->get();

      return $q;
    } 
}
