<?php

namespace App\Http\Controllers\Test;
use DB;
use App\Http\Controllers\Controller;

class Migrate extends Controller {
    public function index() {
      // include this code in reports and message history
      $db_ext = \DB::connection('mysql_external');
        $countries = $db_ext->table('tbl_subscriber_notes')->get();
        print_r($countries);
    }
    public function notes(){
      $db = \DB::connection('mysql_external');
        
        $notes =  DB::connection('mysql_external')->select('SELECT sn.`id`, cs.`id` AS conversation_id, sn.`user_id`, sn.`comment`, IF(sn.`comment` LIKE "pun%",  "subscriber", "persona") AS comment_type, sn.`date_created`
FROM tbl_subscriber_notes sn, tbl_conversations cs
WHERE cs.`persona_id` = sn.`persona_id`
AND cs.`subscriber_id` = sn.`subscriber_id`
AND sn.`id` > 5000000 AND sn.`id` < 6000000');      
     
      foreach($notes as $note){
        $data = array(
            array(
              "conversation_id" => $note->conversation_id,
              "user_id" => $note->user_id,
              "comment" => $note->comment,
              "date_created" => $note->date_created,
              "type" => $note->comment_type
              )
            );


        
       DB::connection('mysql')->table('tbl_conversation_notes')->insert($data);

       
      }
      echo count($notes)."inserted";

}
}


