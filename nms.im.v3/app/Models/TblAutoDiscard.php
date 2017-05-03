<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TblAutoDiscard extends Model {

    public $timestamps = false;

    protected $table = 'tbl_auto_discard';
    protected $fillable = ['service_id', 'start_time', 'end_time'];


    public function tblServices() {
        return $this->belongsTo(\App\Models\TblServices::class, 'service_id', 'id');
    }


}
