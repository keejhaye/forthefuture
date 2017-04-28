<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TblOutboundMessageSpamLogs extends Model {

    public $timestamps = false;

    protected $table = 'tbl_outbound_message_spam_logs';
    protected $fillable = ['user_id', 'ip_address', 'message', 'date_created'];



}
