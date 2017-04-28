<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TblLibraries extends Model {

    public $timestamps = false;

    protected $table = 'tbl_libraries';
    protected $fillable = ['name', 'type', 'date_created', 'status'];


    public function tblServices() {
        return $this->belongsToMany(\App\Models\TblServices::class, 'tbl_libraries_services', 'library_id', 'service_id');
    }

    public function tblActions() {
        return $this->hasMany(\App\Models\TblActions::class, 'library_id', 'id');
    }

    public function tblAutoreminders() {
        return $this->hasMany(\App\Models\TblAutoreminders::class, 'library_id', 'id');
    }

    public function tblLibrariesServices() {
        return $this->hasMany(\App\Models\TblLibrariesServices::class, 'library_id', 'id');
    }

    public function tblLibraryMessages() {
        return $this->hasMany(\App\Models\TblLibraryMessages::class, 'library_id', 'id');
    }


}
