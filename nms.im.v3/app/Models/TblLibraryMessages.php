<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TblLibraryMessages extends Model {

    public $timestamps = false;

    protected $table = 'tbl_library_messages';
    protected $fillable = ['library_id', 'type', 'message', 'date_created', 'position'];


    public function TblLibraries() {
        return $this->belongsTo(\App\Models\TblLibraries::class, 'library_id', 'id');
    }

	public static function get_next_library_message($subscriber_id, $rule){
		$conversation_autoresponded_messages = \TblAutorespondedMessages::get_by_conversationID_libraryID($subscriber_id,$rule->library_id);
		if(!empty($conversation_autoresponded_messages)){
			if($rule->type == 'random'){
				$message_ids = '';
				foreach ($conversation_autoresponded_messages as $message) {
				  $message_ids .= $message->library_message_id . ',';
				}
				$message_ids = rtrim($message_ids, ",");
				$where_clause = " WHERE id not in({$message_ids}) ";
				$order_by_clause = ' ORDER BY RAND() '; 
			}
			else{
				$order_by_clause = ' ORDER BY position ASC '; 
				$where_clause = " WHERE position > ".$conversation_autoresponded_messages[0]->position." "; 
			}

			$statement = "SELECT * 
				            FROM tbl_library_messages 
				            {$where_clause}
				              AND library_id = {$rule->library_id} 
				            {$order_by_clause}
				            LIMIT 1";

			$result = \DB::select(\DB::raw($statement));
			//THERE IS A NEXT AVAILABLE MESSAGE, RETURN IT. IF NOT, SEND FALSE
			return !empty($result) ? $result : false; //$conversation_autoresponded_messages[count($conversation_autoresponded_messages) -1];
		}
		else{ // NO AUTORESPONSE WAS SENT YET FOR THIS CONVERSATION. WE WILL GET ONE MESSAGE FROM THE LIBRARY BASED ON THE TYPE OF RULE(SEQUENTIAL OR RANDOM)

			$statement = "SELECT * FROM tbl_library_messages WHERE library_id = {$rule->library_id}";

			if($rule->type == 'sequential'){
				$statement .= " ORDER BY position ASC LIMIT 1"; 
				$result = \DB::select(\DB::raw($statement));
			}
			else if($rule->type == 'random'){
				$statement .= " ORDER BY RAND()";

				$message_ids = array();
				$result = \DB::select(\DB::raw($statement));

				foreach ($result as $message) {
				  array_push($message_ids, $message->id);
				}
				$random_message_id = $message_ids[rand(0,(count($result) - 1))];
				$statement = "SELECT * FROM tbl_library_messages WHERE id = {$random_message_id}";
				$result = \DB::select(\DB::raw($statement));
			}

			return $result;
		}
	}
}
