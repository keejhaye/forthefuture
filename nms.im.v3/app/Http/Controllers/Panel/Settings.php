<?php

namespace App\Http\Controllers\Panel;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class Settings extends Controller {

    public function index() {
        return view('panel/SettingsContent')->with('page_title', 'Settings');
    }

    public function maximum_conversation(){
         return \TblSettings::find(1);
    }

     public function unmap_time_from_operator(){
         return \TblSettings::find(2);
    }

    public function set_max_conversation(Request $request){
      $max_conversation = \TblSettings::find(1);

      $max_conversation->value = $request->input('value');
      $max_conversation->save();

      return 0;
    }

     public function set_unmap_time(Request $request){
      $unmap_time = \TblSettings::find(2);

      $unmap_time->value = $request->input('value');
      $unmap_time->save();
    }
   
}
