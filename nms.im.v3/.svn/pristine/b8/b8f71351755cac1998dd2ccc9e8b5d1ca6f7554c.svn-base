<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TblAutoremindersQueue extends Model {

    public $timestamps = false;

    protected $table = 'tbl_autoreminders_queue';
    protected $fillable = ['reminder_id', 'execution', 'status', 'executed', 'date_created'];


    public function tblAutoremindedMessages() {
        return $this->belongsTo(\App\Models\TblAutoremindedMessages::class, 'reminder_id', 'id');
    }


}
