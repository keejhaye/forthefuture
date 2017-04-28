<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TblOperatorResponseLogs extends Model {

    public $timestamps = false;

    protected $table = 'tbl_operator_response_logs';
    protected $fillable = ['user_id', 'inbound_id', 'outbound_id', 'inbound_time', 'outbound_time', 'response_interval', 'date_created'];


    public function tblMessages() {
        return $this->belongsTo(\App\Models\TblMessages::class, 'outbound_id', 'id');
    }

    public function tblMessages() {
        return $this->belongsTo(\App\Models\TblMessages::class, 'inbound_id', 'id');
    }

    public function rptOperatorResponseLogs() {
        return $this->hasMany(\App\Models\RptOperatorResponseLogs::class, 'operator_response_log_id', 'id');
    }


}
