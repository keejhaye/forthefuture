<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TblCannedMessages extends Model {

    public $timestamps = false;

    protected $table = 'tbl_canned_messages';
    protected $fillable = ['service_id', 'label', 'message', 'date_created'];


    public function tblServices() {
        return $this->belongsTo(\App\Models\TblServices::class, 'service_id', 'id');
    }


}
