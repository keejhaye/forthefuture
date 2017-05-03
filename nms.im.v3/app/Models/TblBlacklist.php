<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TblBlacklist extends Model {

    public $timestamps = false;

    protected $table = 'tbl_blacklist';
    protected $fillable = ['subscriber_id', 'user_id', 'date_created'];


    public function tblSubscribers() {
        return $this->belongsTo(\App\Models\TblSubscribers::class, 'subscriber_id', 'id');
    }

    public function tblUsers() {
        return $this->belongsTo(\App\Models\TblUsers::class, 'user_id', 'id');
    }
    
    static function get_details($id){
      $result = TblBlacklist::table('tbl_blacklist')
    ->join('tblusers', 'tbl_users.id', '=', 'tbl_blacklist.user_id')
    ->join('tbl_subscribers', 'tbl_subscribers.subscriber_id', '=', 'tbl_blacklist.subscriber_id')
    ->join('tbl_services', 'tbl_services.id', '=', 'tbl_subscribers.service_id')
    ->where('tbl_blacklist.subscriber_id', '=', $id)
    ->get();
    
    }


}
