<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TblLibrariesServices extends Model {

    public $timestamps = false;

    protected $table = 'tbl_libraries_services';
    protected $fillable = ['library_id', 'service_id', 'date_created'];


    public function tblLibraries() {
        return $this->belongsTo(\App\Models\TblLibraries::class, 'library_id', 'id');
    }

    public function tblServices() {
        return $this->belongsTo(\App\Models\TblServices::class, 'service_id', 'id');
    }


}
