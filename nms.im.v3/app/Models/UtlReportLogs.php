<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UtlReportLogs extends Model {

    public $timestamps = false;

    protected $table = 'utl_report_logs';
    protected $fillable = ['user_id', 'username', 'report', 'time_started', 'time_ended', 'date_created', 'from_date', 'to_date', 'timezone'];



}
