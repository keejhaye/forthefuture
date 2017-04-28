<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UtlMigrationV1Status extends Model {

    public $timestamps = false;

    protected $table = 'utl_migration_v1_status';
    protected $fillable = ['offset_start', 'offset_end', 'limit', 'service_id', 'status', 'date_started', 'iteration'];



}
