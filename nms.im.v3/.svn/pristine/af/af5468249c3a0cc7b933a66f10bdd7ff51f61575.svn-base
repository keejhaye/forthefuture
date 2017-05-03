<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TblAutoremindedMessages extends Model {

    public $timestamps = false;

    protected $table = 'tbl_autoreminded_messages';
    protected $fillable = ['message_id', 'autoreminder_id', 'time_reminded', 'date_created', 'persona_id', 'subscriber_id', 'last_outbound', 'status'];


    public function tblAutoreminders() {
        return $this->belongsTo(\App\Models\TblAutoreminders::class, 'autoreminder_id', 'id');
    }

    public function tblAutoremindersQueue() {
        return $this->hasMany(\App\Models\TblAutoremindersQueue::class, 'reminder_id', 'id');
    }


}
