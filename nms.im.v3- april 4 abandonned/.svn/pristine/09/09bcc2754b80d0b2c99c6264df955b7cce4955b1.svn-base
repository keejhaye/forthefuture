<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TblDelayedMessages extends Model {

    public $timestamps = false;

    protected $table = 'tbl_delayed_messages';
    protected $fillable = ['message_id', 'delay_until', 'date_created'];


    public function tblMessages() {
        return $this->belongsTo(\App\Models\TblMessages::class, 'message_id', 'id');
    }


}
