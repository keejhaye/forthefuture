<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TblAutoreminders extends Model {

    public $timestamps = false;

    protected $table = 'tbl_autoreminders';
    protected $fillable = ['service_id', 'library_id', 'idle_time', 'schedule', 'timezone', 'date_created', 'status'];


    public function tblLibraries() {
        return $this->belongsTo(\App\Models\TblLibraries::class, 'library_id', 'id');
    }

    public function tblServices() {
        return $this->belongsTo(\App\Models\TblServices::class, 'service_id', 'id');
    }

    public function tblAutoremindedMessages() {
        return $this->hasMany(\App\Models\TblAutoremindedMessages::class, 'autoreminder_id', 'id');
    }


}
