<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TblRoles extends Model {

    public $timestamps = false;

    protected $table = 'tbl_roles';
    protected $fillable = ['name', 'description', 'permissions', 'level', 'reportable'];


    public function tblUsers() {
        return $this->hasMany(\App\Models\TblUsers::class, 'role_id', 'id');
    }


}
