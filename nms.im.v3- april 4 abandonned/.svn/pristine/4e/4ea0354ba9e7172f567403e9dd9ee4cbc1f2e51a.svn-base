<?php

namespace App\Http\Controllers\Test;
use DB;
use App\Http\Controllers\Controller;

class SwitchDB extends Controller {
    public function index() {
      // include this code in reports and message history
      $db_ext = \DB::connection('mysql_external');
        $countries = $db_ext->table('tbl_users')->get();
        print_r($countries);
}
    }


