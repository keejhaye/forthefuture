<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UtlStatistics extends Model {

    public $timestamps = false;

    protected $table = 'utl_statistics';
    protected $fillable = ['name', 'description', 'time_executed', 'executed'];

    public static function get_stats($name) {
        $stat = \UtlStatistics::whereName($name)->first();
        return $stat;
    }
}
