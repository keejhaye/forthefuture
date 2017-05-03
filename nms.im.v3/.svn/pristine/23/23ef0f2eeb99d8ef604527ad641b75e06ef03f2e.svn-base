<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TblMessagesToDiscard extends Model {

    public $timestamps = false;

    protected $table = 'tbl_messages_to_discard';
    protected $fillable = ['service_id', 'phrase', 'action', 'date_created'];


    public function tblServices() {
        return $this->belongsTo(\App\Models\TblServices::class, 'service_id', 'id');
    }


}
