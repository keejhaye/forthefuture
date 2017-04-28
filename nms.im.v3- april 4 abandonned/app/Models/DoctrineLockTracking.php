<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctrineLockTracking extends Model {

    public $timestamps = false;

    protected $table = 'doctrine_lock_tracking';
    protected $fillable = ['object_type', 'object_key', 'user_ident', 'timestamp_obtained'];



}
