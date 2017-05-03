<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TblConditions extends Model {

    public $timestamps = false;

    protected $table = 'tbl_conditions';
    protected $fillable = ['rule_id', 'field', 'operator', 'value', 'status', 'date_created', 'conditional_operator'];


    public function tblRules() {
        return $this->belongsTo(\App\Models\TblRules::class, 'rule_id', 'id');
    }


}
