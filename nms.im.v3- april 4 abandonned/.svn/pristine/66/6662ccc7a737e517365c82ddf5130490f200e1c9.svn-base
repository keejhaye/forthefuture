<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TblFlaggedMessageDeductions extends Model {

    public $timestamps = false;

    protected $table = 'tbl_flagged_message_deductions';
    protected $fillable = ['flagged_message_id', 'deduction', 'date_moderated'];


    public function tblFlaggedMessages() {
        return $this->belongsTo(\App\Models\TblFlaggedMessages::class, 'flagged_message_id', 'id');
    }


}
