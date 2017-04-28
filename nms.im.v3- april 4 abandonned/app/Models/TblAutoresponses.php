<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TblAutoresponses extends Model {

    public $timestamps = false;

    protected $table = 'tbl_autoresponses';
    protected $fillable = ['service_id', 'message_condition', 'keyword', 'message', 'date_created'];


    public function tblServices() {
        return $this->belongsTo(\App\Models\TblServices::class, 'service_id', 'id');
    }

    public function tblMessages() {
        return $this->belongsToMany(\App\Models\TblMessages::class, 'tbl_autoresponse_queue', 'autoresponse_id', 'message_id');
    }

    public function tblAutoresponseQueue() {
        return $this->hasMany(\App\Models\TblAutoresponseQueue::class, 'autoresponse_id', 'id');
    }

    static function get_service_and_persona_rules($persona_id, $service_id){
        $statement = "SELECT r.*,l.type 
                      FROM tbl_autoresponses r 
                      LEFT JOIN tbl_libraries l on r.library_id = l.id
                      WHERE r.service_id = {$service_id} 
                        AND (persona_id = {$persona_id} or persona_id is null)
                      ORDER BY persona_id DESC";
                      
        return \DB::select($statement);
    }
}
