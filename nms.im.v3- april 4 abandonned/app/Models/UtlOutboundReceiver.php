<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UtlOutboundReceiver extends Model {

    public $timestamps = false;

    protected $table = 'utl_outbound_receiver';
    protected $fillable = ['url', 'request', 'ip_address', 'date_created', 'reference', 'response', 'failed', 'service_code', 'type', 'execution_info'];



}
