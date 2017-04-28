<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TblActions extends Model {

    public $timestamps = false;

    protected $table = 'tbl_actions';
    protected $fillable = ['rule_id', 'library_id', 'operator_id', 'type', 'meta', 'assign_delay', 'date_created', 'status'];


    public function tblLibraries() {
        return $this->belongsTo(\App\Models\TblLibraries::class, 'library_id', 'id');
    }

    public function tblUsers() {
        return $this->belongsTo(\App\Models\TblUsers::class, 'operator_id', 'id');
    }

    public function tblRules() {
        return $this->belongsTo(\App\Models\TblRules::class, 'rule_id', 'id');
    }

    public function tblMessages() {
        return $this->belongsToMany(\App\Models\TblMessages::class, 'tbl_actions_queue', 'action_id', 'message_id');
    }

    public function tblActionsQueue() {
        return $this->hasMany(\App\Models\TblActionsQueue::class, 'action_id', 'id');
    }


}
