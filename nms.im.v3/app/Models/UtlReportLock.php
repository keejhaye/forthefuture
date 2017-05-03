<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UtlReportLock extends Model {

    public $timestamps = false;

    protected $table = 'utl_report_lock';
    protected $fillable = ['object_key', 'user_id', 'username', 'report', 'date_created'];



}
