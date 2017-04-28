<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TblWhitelist extends Model {

    public $timestamps = false;

    protected $table = 'tbl_whitelist';
    protected $fillable = ['service_id', 'ip_address', 'date_created'];


    public function tblServices() {
        return $this->belongsTo(\App\Models\TblServices::class, 'service_id', 'id');
    }
    
    public static function find_by_service($service_id, $ip = null){
         $q = \TblWhitelist::where('service_id', $service_id);
         
         if($ip != null){
             $q->where('ip_address', $ip);
         }
         
         return $q->get();
    }

}
