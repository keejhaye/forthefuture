<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TblPersonaFiles extends Model {

    public $timestamps = false;

    protected $table = 'tbl_persona_files';
    protected $fillable = ['persona_id', 'path', 'path_thumb', 'file', 'description', 'date_created'];


    public function tblPersonas() {
        return $this->belongsTo(\App\Models\TblPersonas::class, 'persona_id', 'id');
    }


}
