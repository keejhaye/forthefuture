<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class TblPersonas extends Model {

    public $timestamps = false;

    protected $table = 'tbl_personas';
    protected $fillable = ['name', 'service_id', 'status', 'profile', 'date_created', 'additional_info'];


    public function tblServices() {
        return $this->belongsTo(\App\Models\TblServices::class, 'service_id', 'id');
    }

    public function tblSubscribers() {
        return $this->belongsToMany(\App\Models\TblSubscribers::class, 'tbl_subscriber_persona_limit', 'persona_id', 'subscriber_id');
    }

    public function tblConversations() {
        return $this->hasMany(\App\Models\TblConversations::class, 'persona_id', 'id');
    }

    public function tblConversationsMod() {
        return $this->hasMany(\App\Models\TblConversationsMod::class, 'persona_id', 'id');
    }

    public function tblPersonaFiles() {
        return $this->hasMany(\App\Models\TblPersonaFiles::class, 'persona_id', 'id');
    }

    public function tblSubscriberNotes() {
        return $this->hasMany(\App\Models\TblSubscriberNotes::class, 'persona_id', 'id');
    }

    public function tblSubscriberPersonaLimit() {
        return $this->hasMany(\App\Models\TblSubscriberPersonaLimit::class, 'persona_id', 'id');
    }

    public static function get_persona($params){
        $q = \TblPersonas::select('tbl_personas.*')
                  ->where('tbl_personas.name', $params['name'])
                  ->where('tbl_personas.service_id', $params['service_id'])
                  ->get();
        
        return $q;
    }

    public static function search($params){
        $query = \DB::table('tbl_personas AS p');

        if(isset($params['keyword']) && ($params['keyword'] != ""))
            $query->where('p.name', 'LIKE', "%".$params['keyword']."%");

        if(isset($params['limit']) && ($params['limit'] != ""))
            $query->limit($params['limit']);

        return $query;
    }
}
