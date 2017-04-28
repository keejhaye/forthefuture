<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TblGroups extends Model {

    public $timestamps = false;

    protected $table = 'tbl_groups';
    protected $fillable = ['name', 'description'];


    public function tblServices() {
        return $this->belongsToMany(\App\Models\TblServices::class, 'tbl_service_group', 'group_id', 'service_id');
    }

    public function tblServiceGroup() {
        return $this->hasMany(\App\Models\TblServiceGroup::class, 'group_id', 'id');
    }


}
