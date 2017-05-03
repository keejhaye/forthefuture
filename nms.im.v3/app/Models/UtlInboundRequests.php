<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UtlInboundRequests extends Model {

    public $timestamps = false;

    protected $table = 'utl_inbound_requests';
    protected $fillable = ['url', 'request', 'ip_address', 'date_created', 'reference', 'response', 'failed', 'service_code', 'type', 'execution_info'];



}
