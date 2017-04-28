<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TblSubscribers extends Model {

    public $timestamps = false;
    protected $table = 'tbl_subscribers';
    protected $fillable = ['name', 'service_id', 'status', 'profile', 'date_created', 'additional_info', 'membership_type'];

    public function tblServices() {
        return $this->belongsTo(\App\Models\TblServices::class, 'service_id', 'id');
    }

    public function tblUsers() {
        return $this->belongsToMany(\App\Models\TblUsers::class, 'tbl_blacklist', 'subscriber_id', 'user_id');
    }

    public function tblPersonas() {
        return $this->belongsToMany(\App\Models\TblPersonas::class, 'tbl_subscriber_persona_limit', 'subscriber_id', 'persona_id');
    }

    public function tblBlacklist() {
        return $this->hasMany(\App\Models\TblBlacklist::class, 'subscriber_id', 'id');
    }

    public function tblConversations() {
        return $this->hasMany(\App\Models\TblConversations::class, 'subscriber_id', 'id');
    }

    public function tblConversationsMod() {
        return $this->hasMany(\App\Models\TblConversationsMod::class, 'subscriber_id', 'id');
    }

    public function tblSubscriberBilling() {
        return $this->hasMany(\App\Models\TblSubscriberBilling::class, 'subscriber_id', 'id');
    }

    public function tblSubscriberMessageLimit() {
        return $this->hasMany(\App\Models\TblSubscriberMessageLimit::class, 'subscriber_id', 'id');
    }

    public function tblSubscriberNotes() {
        return $this->hasMany(\App\Models\TblSubscriberNotes::class, 'subscriber_id', 'id');
    }

    public function tblSubscriberPersonaLimit() {
        return $this->hasMany(\App\Models\TblSubscriberPersonaLimit::class, 'subscriber_id', 'id');
    }

    public function _search($params) {
        $q = DB::table('TblSubscribers')
                  ->where('TblSubscribers.name', $params['name'])
                  ->andWhere('TblSubscribers.service_id', $params['name'])
                  ->get();
        return $q;
    }

    public static function st_search() {
        return $this->search($params);
    }
    
    public static function get_subscriber($params){
        $q = \TblSubscribers::select('tbl_subscribers.*')
                  ->where('tbl_subscribers.name', $params['name'])
                  ->where('tbl_subscribers.service_id', $params['service_id'])
                  ->get();
        
        return $q;
    }

    public static function search($params){
        $query = \DB::table('tbl_subcribers AS s');

        if(isset($params['keyword']) && ($params['keyword'] != ""))
            $query->where('s.name', 'LIKE', "%".$params['keyword']."%");

        if(isset($params['limit']) && ($params['limit'] != ""))
            $query->limit($params['limit']);

        return $query;
    }

}
