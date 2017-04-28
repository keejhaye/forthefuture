<?php

namespace App\Http\Controllers\Panel;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class Autocomplete extends Controller {

    public function index() {
        die('!');
    }

     public function search_service(Request $request) {
        $query = $request->get('term','');
        
        $services=\TblServices::where('name','LIKE','%'.$query.'%');
        
        if(session()->get('user.role_id') == 6){
            $services->whereIn('id', session()->get('user.services'));
        }

        $services = $services->get();

        $data=array();
        foreach ($services as $service) {
                $data[]=array('value'=>$service->name,'id'=>$service->id);
        }
        if(count($data))
             return $data;
        else
            return ['value'=>'No Result Found','id'=>''];
    }

     public function search_user(Request $request) {
        $query = $request->get('term','');
        
        $users=\TblUsers::where('username','LIKE','%'.$query.'%')->get();
        
        $data=array();
        foreach ($users as $user) {
                $data[]=array('value'=>$user->username,'id'=>$user->id);
        }
        if(count($data))
             return $data;
        else
            return ['value'=>'No Result Found','id'=>''];
    }

}
