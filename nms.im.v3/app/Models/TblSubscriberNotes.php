<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TblSubscriberNotes extends Model {

    public $timestamps = false;

    protected $table = 'tbl_subscriber_notes';
    protected $fillable = ['user_id', 'persona_id', 'subscriber_id', 'comment', 'date_created'];


    public function tblSubscribers() {
        return $this->belongsTo(\App\Models\TblSubscribers::class, 'subscriber_id', 'id');
    }

    public function tblPersonas() {
        return $this->belongsTo(\App\Models\TblPersonas::class, 'persona_id', 'id');
    }

    public function tblUsers() {
        return $this->belongsTo(\App\Models\TblUsers::class, 'user_id', 'id');
    }


}