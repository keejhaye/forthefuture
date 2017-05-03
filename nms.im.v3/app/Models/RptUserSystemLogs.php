<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RptUserSystemLogs extends Model {

    public $timestamps = false;

    protected $table = 'rpt_user_system_logs';
    protected $fillable = ['user_log_id', 'user_id', 'fullname', 'status', 'start_time', 'end_time', 'date_created', 'active', 'ip_address', 'remarks'];

}
