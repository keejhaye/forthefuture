<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TblOutboundQueue extends Model {

    public $timestamps = false;

    protected $table = 'tbl_outbound_queue';
    protected $fillable = ['details', 'status', 'outbound_time', 'execution', 'executed', 'execution_info', 'date_created', 'remarks', 'is_failed'];

  public static function delete_processed_messages(){
    $limit = 500;
    $cnt = 0;
    $ids = array();

    $con = Doctrine_Manager::connection();

    $statement = "SELECT oq.id
                  FROM tbl_outbound_queue oq
                  WHERE oq.status = 'done'
                  AND oq.is_failed = 0
                  LIMIT {$limit}";        
    $results = $con->fetchAssoc($statement);

    if(count($results) > 0){
      foreach($results as $key => $id){
        $ids[] = $id["id"];
        $cnt++;
      }
    }
    
    if(count($ids) > 0){
      $statement2 = "DELETE FROM tbl_outbound_queue
                    WHERE id IN (".implode(",", $ids).")";    
      $con->exec($statement2);      
    }
    
    return $cnt;
  } 

}
