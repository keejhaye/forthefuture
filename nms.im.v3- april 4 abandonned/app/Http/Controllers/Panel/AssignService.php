<?php

namespace App\Http\Controllers\Panel;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\TblUserService;
use Session;

class AssignService extends Controller {

    public function index() {
        $users = \TblUsers::orderBy('id')->get();
        $services = \TblServices::orderBy('id')->get();
        return view('panel/AssignServiceOperator')
            ->with('page_title', 'Services')
            ->with('users', $users)
            ->with('services', $services);
    }

    public function store(Request $request){

        if(!$request->input('mapOperToServId') || !$request->input('mapServToOperId')){
            return 1;
        }
        $operatorId = $request->input('mapOperToServId');
        $serviceId = $request->input('mapServToOperId');

        for ($i=0; $i < count($operatorId); $i++) {

            for ($ii=0; $ii < count($serviceId); $ii++) { 
                $userService = new \TblUserService;
                $storeServices = $userService::where([
                        'user_id' => $operatorId[$i],
                        'service_id' => $serviceId[$ii]
                    ])->first();

                if ($storeServices === null ) {
                    $userService->user_id = $operatorId[$i];
                    $userService->service_id = $serviceId[$ii];
                    $userService->date_created = gmdate('Y-m-d H:i:s');
                    $userService->save();
                }

            }

        }

        Session::flash('successBatchAssign', 'Batch Assign completed.');
        return back();
    }


    public function getAllOperatorById($servId){
        $services = new \TblUserService;
        $serv = $services::getUserNameById($servId);
        echo json_encode($serv);
    }


    public function getAllServById($operId){
        $services = new \TblUserService;
        $oper = $services::getServiceNameById($operId);
        echo json_encode($oper);
    }


    public function deleteUserService(Request $request, $ifServiceOrOperator){
        $unmapServId = $request->input('unmapServId');
        $unmapServToOperId = $request->input('unmapServToOperId');

        if(!$unmapServId || !$unmapServToOperId){
            return 1;
        }
        for ($i=0; $i < count($unmapServToOperId); $i++) { 
            $userService = new \TblUserService;
            if ($ifServiceOrOperator) {
                //This if operator mapping.
                    $deleteServices = $userService::where([
                            'service_id' => $unmapServToOperId[$i],
                            'user_id' => $unmapServId
                        ])->delete();
            }else {
                //This if service mapping.
                    $deleteServices = $userService::where([
                            'service_id' => $unmapServId,
                            'user_id' => $unmapServToOperId[$i]
                        ])->delete();
            }
        }

        Session::flash('deletedBatchAssign', 'Batch unassigned.');
        return back();

    }








}
