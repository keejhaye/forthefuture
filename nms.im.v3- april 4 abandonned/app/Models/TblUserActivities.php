<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TblUserActivities extends Model {

    public $timestamps = false;

    protected $table = 'tbl_user_activities';
    protected $fillable = ['user_id', 'old_data', 'new_data', 'section', 'changed_fields', 'is_new', 'record_changed', 'remarks', 'date_created'];


    public function tblUsers() {
        return $this->belongsTo(\App\Models\TblUsers::class, 'user_id', 'id');
    }


}
