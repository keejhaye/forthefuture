<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TblRules extends Model {

    public $timestamps = false;

    protected $table = 'tbl_rules';
    protected $fillable = ['service_id', 'name', 'delay', 'run_once', 'date_created', 'priority', 'status'];


    public function tblServices() {
        return $this->belongsTo(\App\Models\TblServices::class, 'service_id', 'id');
    }

    public function tblActions() {
        return $this->hasMany(\App\Models\TblActions::class, 'rule_id', 'id');
    }

    public function tblConditions() {
        return $this->hasMany(\App\Models\TblConditions::class, 'rule_id', 'id');
    }


}
