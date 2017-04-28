<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TblFlaggedMessagesStatistic extends Model {

    public $timestamps = false;

    protected $table = 'tbl_flagged_messages_statistics';
    protected $fillable = ['operator_id', 'flagged', 'approved', 'rejected', 'pending', 'last_reset', 'discarded'];


    public function tblFlaggedMessages() {
        return $this->belongsTo(\App\Models\TblFlaggedMessages::class, 'operator_id', 'operator_id');
    }


}
