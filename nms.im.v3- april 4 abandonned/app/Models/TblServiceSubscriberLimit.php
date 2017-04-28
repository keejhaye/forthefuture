<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TblServiceSubscriberLimit extends Model {

    public $timestamps = false;

    protected $table = 'tbl_service_subscriber_limit';
    protected $fillable = ['service_id', 'limit_type', 'limit_count', 'limit_action', 'reset_period', 'in_seconds'];


    public function tblServices() {
        return $this->belongsTo(\App\Models\TblServices::class, 'service_id', 'id');
    }


}
