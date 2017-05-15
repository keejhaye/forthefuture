<?php

namespace App\Http\Controllers\Panel;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class Personas extends Controller {

    public function index() {
        return view('panel/PersonasContent')->with('page_title', 'Personas');
    }

    //  -- Kris' changes 5/8/2017
    public function getPersonasByPage($offset, $limit, $pname = null){

        $personas = \TblPersonas::orderBy('id', 'asc')->skip($offset)->take($limit); 
        
        if(session()->get('user.role_id') == 6){
            $personas->whereIn('service_id', session()->get('user.services'));
        }
        if ($pname !== null) {
            $personas->where('name', 'like', '%'.$pname.'%');
        }
        return $personas->get(); 
    }

    public function countAllPersonas(){
        $cnt = \TblPersonas::orderBy('id', 'asc');

        if(session()->get('user.role_id') == 6){
            $cnt->whereIn('service_id', session()->get('user.services'));
        }
        return $cnt->count();
    }

    public function getSearchPersonas($searchCol, $searchKey, $searchParams = null){
        var_dump($searchParams);
        $searchPersonas = \TblPersonas::orderBy('id', 'asc');
        $searchPersonas->leftJoin('tbl_services', 'tbl_personas.service_id', '=', 'tbl_services.id');
        $searchPersonas->select('tbl_personas.*', 'tbl_services.name AS service_name');
        if ($searchParams !== null) {
            $aSearch = json_decode($searchParams, true);
            if(isset($aSearch[0]['name'])){
                $searchPersonas->where('tbl_personas.name', 'like', '%'.$aSearch[0]['name'].'%');
            } 
            if(isset($aSearch[0]['service_id'])){
                $searchPersonas->where('tbl_services.name', 'like', '%'.$aSearch[0]['service_id'].'%');
            } 
            if(isset($aSearch[0]['persona_status'])){
                $searchPersonas->where('tbl_personas.status', $aSearch[0]['persona_status']);
            } 
        }
        return $searchPersonas->get(); 
    }
    //  -- Kris' changes 5/8/2017

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
