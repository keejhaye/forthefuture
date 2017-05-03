<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TblBulletinUser extends Model {

    public $timestamps = false;

    protected $table = 'tbl_bulletin_user';
    protected $fillable = ['bulletin_id', 'user_id', 'approved', 'date_created'];


    public function tblBulletins() {
        return $this->belongsTo(\App\Models\TblBulletins::class, 'bulletin_id', 'id');
    }

    public function tblUsers() {
        return $this->belongsTo(\App\Models\TblUsers::class, 'user_id', 'id');
    }


}
