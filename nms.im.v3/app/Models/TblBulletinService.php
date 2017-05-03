<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TblBulletinService extends Model {

    public $timestamps = false;

    protected $table = 'tbl_bulletin_service';
    protected $fillable = ['bulletin_id', 'service_id', 'date_created'];


    public function tblBulletins() {
        return $this->belongsTo(\App\Models\TblBulletins::class, 'bulletin_id', 'id');
    }

    public function tblServices() {
        return $this->belongsTo(\App\Models\TblServices::class, 'service_id', 'id');
    }


}
