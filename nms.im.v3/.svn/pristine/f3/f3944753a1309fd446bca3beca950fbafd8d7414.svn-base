<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TblActionsQueue extends Model {

    public $timestamps = false;

    protected $table = 'tbl_actions_queue';
    protected $fillable = ['action_id', 'message_id', 'execution', 'status', 'executed', 'notes'];


    public function tblActions() {
        return $this->belongsTo(\App\Models\TblActions::class, 'action_id', 'id');
    }

    public function tblMessages() {
        return $this->belongsTo(\App\Models\TblMessages::class, 'message_id', 'id');
    }


}
