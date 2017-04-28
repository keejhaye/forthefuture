<?php

namespace App\Http\Controllers\Panel;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class Personas extends Controller {

    public function index() {
        return view('panel/PersonasContent')->with('page_title', 'Personas');
    }

    public function personas($id = null) {
        if ($id == null) {
            $personas = \TblPersonas::orderBy('id', 'asc');
            
            if(session()->get('user.role_id') == 6){
                $personas->whereIn('service_id', session()->get('user.services'));
            }

            return $personas->get();
        } else {
            return $this->show($id);
        }
    }

     public function show($id) {
        return \TblPersonas::find($id);
    }
      public function add(Request $request) {
        $persona = new \TblPersonas;
     
        $persona->name = $request->input('name');
        $persona->service_id = $request->input('service_id');
        $persona->status = $request->input('status'); 
        $persona->profile = json_encode('profile');
        $persona->additional_info = json_encode('additional_info');
        $persona->date_created = gmdate('Y-m-d H:i:s');
        
        $persona->save();

        return 0;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id) {
        $persona = \TblPersonas::find($id);
     
        $profile = $this->profile_to_array( $request->input('profile'));
        $persona->name = $request->input('name');
        $persona->service_id = $request->input('service_id');
        $persona->status = $request->input('status'); 
        $persona->profile = json_encode($profile);
        $persona->additional_info = json_encode('additional_info');
        $persona->date_created = gmdate('Y-m-d H:i:s');
        
        $persona->save();

        return 0;
    }
    
    public function profile_to_array($profile) {
    $data = array();
    $profile = str_replace("<p>", "", $profile);
    $profile = str_replace("</p>", "<br />", $profile);
    $array = explode("<br />", $profile);
    $cnt = count($array);
    $i = 0;
    foreach ($array as $key => $item) {
      $i++;
      if ($i < $cnt) {
        $temp = explode(":", $item);
        $index = trim(strtolower($temp[0]));
        $data[$index] = trim($temp[1]);
      }
    }
    return $data;
  }


}
