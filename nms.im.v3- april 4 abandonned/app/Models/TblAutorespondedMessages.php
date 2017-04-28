<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TblAutorespondedMessages extends Model {
    
    public $timestamps = false;
    protected $table = 'tbl_autoresponded_messages';
    protected $fillable = ['id', 'message_id', 'library_message_id', 'date_created', 'date_executed'];


    public function tblMessagesMod() {
        return $this->belongsTo(\App\Models\TblLibraries::class, 'message_id', 'id');
    }

    public function tblLibraryMessage() {
        return $this->belongsTo(\App\Models\TblLibraryMessage::class, 'library_message_id', 'id');
    }

    public static function get_by_conversationID_libraryID($subscriber_id, $library_id){

        $statement = "  SELECT am.*,lm.library_id,lm.position 
                        FROM `tbl_autoresponded_messages` am 
                        INNER JOIN `tbl_library_messages` lm ON am.`library_message_id` = lm.`id` 
                        WHERE am.subscriber_id = {$subscriber_id} 
                            AND lm.library_id = {$library_id}
                        ORDER BY am.id DESC";

        $query = \DB::select(\DB::raw($statement));
        return $query;
    }
}
