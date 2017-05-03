<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TblOutboundMessageAttachments extends Model {

    public $timestamps = false;

    protected $table = 'tbl_outbound_message_attachments';
    protected $fillable = ['file', 'path', 'message_id', 'date_created', 'expire_on'];


    public function tblMessages() {
        return $this->belongsTo(\App\Models\TblMessages::class, 'message_id', 'id');
    }


}
