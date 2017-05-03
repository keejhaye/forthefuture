<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TblServiceGroup extends Model {

    public $timestamps = false;

    protected $table = 'tbl_service_group';
    protected $fillable = ['group_id', 'service_id'];


    public function tblServices() {
        return $this->belongsTo(\App\Models\TblServices::class, 'service_id', 'id');
    }

    public function tblGroups() {
        return $this->belongsTo(\App\Models\TblGroups::class, 'group_id', 'id');
    }


}
