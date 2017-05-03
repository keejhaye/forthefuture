<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TblProductionApiLogs extends Model {

    public $timestamps = false;

    protected $table = 'tbl_production_api_logs';
    protected $fillable = ['request','response','report_date','date_created'];
}
