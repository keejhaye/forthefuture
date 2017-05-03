<?php

namespace App\Http\Controllers\Panel;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class Bulletin extends Controller {

    public function index() {
        return view('panel/BulletinContent')->with('page_title', 'Bulletins');
    }

    public function get_bulletin($id = null) {
        if ($id == null) {
            return \TblBulletins::orderBy('id', 'asc')->get();
        } else {
            return $this->show($id);
        }
    }
    
    public function get_services() {
        return \TblServices::orderBy('id', 'asc')->select('id', 'name')->get();
    }

    public function get_bulletin_info($id) {
        return \TblBulletins::find($id);
    }

    public function get_bulletin_info_users($bulletin_id) {
        return \TblBulletinUser::where('bulletin_id', $bulletin_id)->select('user_id as id')->get();
        // return \TblBulletinUser::where('bulletin_id', $bulletin_id)->pluck('user_id');
    }

    public function get_bulletin_info_services($bulletin_id) {
        return \TblBulletinService::where('bulletin_id', $bulletin_id)->select('service_id as id')->get();
        // return \TblBulletinService::where('bulletin_id', $bulletin_id)->pluck('service_id');
    }

    public function search_bulletin(Request $request) {
        if ($request->services == null) {
            return \TblBulletins::orderBy('id', 'asc')->where('title', 'like', '%'.$request->keywords.'%')->get();
        } else {
            return \App\Models\TblServices::find($request->services)->tblBulletins()->orderBy('id', 'asc')->where('title', 'like', '%'.$request->keywords.'%')->get();
        }
    }

    public function show($id) {
        return \TblBulletins::find($id);
    }

    public function get_users() {
        return \TblUsers::where('role_id', '>', 2)->leftJoin('tbl_roles', 'tbl_roles.id', '=', 'tbl_users.role_id')->select('tbl_users.id as id','username as name', 'name as role')->get();
    }

    public function add(Request $request){

        $current_date = gmdate('Y-m-d H:i:s');

        $bulletin = new \App\Models\TblBulletins;
        $bulletin->title = $request->title;
        $bulletin->priority = "normal";
        $bulletin->message = $request->message;
        $bulletin->expires = $request->expires;
        $bulletin->date_created = $current_date;
        $bulletin->save();
        
        if($request->services != null && $bulletin->id != null){
            $services = array();
            $array_service = explode(",", $request->services);
            foreach ($array_service as $key => $value) {
                $services[] = array('bulletin_id'=> $bulletin->id,'service_id'=>$value, 'date_created' => $current_date);
            }
            $insert_services = \App\Models\TblBulletinService::insert($services);
        }

        if($request->recipients != null && $bulletin->id != null){
            $users = array();
            $array_recipients = explode(",", $request->recipients);
            foreach ($array_recipients as $key => $value) {
                $users[] = array('bulletin_id' => $bulletin->id , 'user_id' => $value , 'approved' => '0', 'date_created'=> $current_date);
            }
            $insert_recipients = \App\Models\TblBulletinUser::insert($users);
        }

        return $bulletin->id;
    }

    public function update(Request $request, $id){ 
        $current_date = gmdate('Y-m-d H:i:s');
        
        $bulletin = \App\Models\TblBulletins::find($id);
        $bulletin->title = $request->title;
        $bulletin->priority = "normal";
        $bulletin->message = $request->message;
        $bulletin->expires = $request->expires;
        $bulletin->save();


        if($request->services != null && $id != null){
            $services = array();
            $array_service = array_map('intval', explode(",", $request->services));

            /* Delete Services that was unchecked */
            $services_where_changed = \App\Models\TblBulletinService::where('bulletin_id', $id)->whereNotIn('service_id', $array_service)->delete();
        
            $existing_service = \App\Models\TblBulletinService::where('bulletin_id', $id)->whereIn('service_id', $array_service)->pluck("service_id")->toArray();
           
            $array_service = array_diff($array_service, $existing_service);
            
            foreach ($array_service as $key => $value) {
                $services[] = array('bulletin_id' => $bulletin->id,'service_id' => $value, 'date_created' => $current_date);
            }
            
            $insert_services = \App\Models\TblBulletinService::insert($services);
        }

        if($request->recipients != null && $id != null){
            $users = array();
            
            $array_recipients = array_map('intval', explode(",", $request->recipients));

            /* Delete Users that was unchecked */
            $services_where_changed = \App\Models\TblBulletinUser::where('bulletin_id', $id)->whereNotIn('user_id', $array_recipients)->delete();

            $existing_users = \App\Models\TblBulletinUser::where('bulletin_id', $id)->whereIn('user_id', $array_recipients)->pluck("user_id")->toArray();

            $array_recipients = array_diff($array_recipients, $existing_users);
            
            foreach ($array_recipients as $key => $value) {
                    $users[] = array('bulletin_id' => $bulletin->id , 'user_id' => $value , 'approved' => '0', 'date_created'=> $current_date);
            }
            $insert_recipients = \App\Models\TblBulletinUser::insert($users);
        }

        return $users;

    }
}