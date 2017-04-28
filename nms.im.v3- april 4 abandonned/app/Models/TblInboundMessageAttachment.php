<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TblInboundMessageAttachment extends Model {

    public $timestamps = false;

    protected $table = 'tbl_inbound_message_attachment';
    protected $fillable = ['path', 'message_id', 'date_created', 'expire_on', 'file'];


    public function tblMessages() {
        return $this->belongsTo(\App\Models\TblMessages::class, 'message_id', 'id');
    }


}
