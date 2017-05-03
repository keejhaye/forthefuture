<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TblBulletins extends Model {

    public $timestamps = false;

    protected $table = 'tbl_bulletins';
    protected $fillable = ['title', 'priority', 'message', 'expires', 'date_created', 'images'];


    public function tblServices() {
        return $this->belongsToMany(\App\Models\TblServices::class, 'tbl_bulletin_service', 'bulletin_id', 'service_id');
    }

    public function tblUsers() {
        return $this->belongsToMany(\App\Models\TblUsers::class, 'tbl_bulletin_user', 'bulletin_id', 'user_id');
    }

    public function tblBulletinService() {
        return $this->hasMany(\App\Models\TblBulletinService::class, 'bulletin_id', 'id');
    }

    public function tblBulletinUser() {
        return $this->hasMany(\App\Models\TblBulletinUser::class, 'bulletin_id', 'id');
    }


}
