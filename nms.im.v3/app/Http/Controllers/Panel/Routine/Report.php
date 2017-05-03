<?php

namespace App\Http\Controllers\Panel\Routine;

use Illuminate\Http\Request;
use DateTime;
use DateTimezone;
use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class Report extends Controller{
  
  public function extract_operator_reports(){
    $current_time = gmdate('Y-m-d H:i:s');

   // $start_date = gmdate('Y-m-d H:00:00', strtotime('-1 hour'));
   // $end_date = gmdate('Y-m-d H:59:59', strtotime('-1 hour'));

    $start_date = '2017-04-16 06:00:00';
    $end_date = '2017-04-19 06:59:59';
    $multiQ = \TblServices::select(\DB::raw('DISTINCT(multiplier)'))->get()->toArray();
     $results["multipliers"] = $multiQ;
     if (count($results["multipliers"]) > 0) {
            foreach ($results["multipliers"] as $key => $value) {
                $multiplier = $value['multiplier'];
                $multiplier_subquery = "SUM(IF(s.multiplier = '$multiplier', 1, 0)) AS 'x$multiplier'";
            }
        }

    $basequery = "SELECT rm.user_id AS rm_user_id, rm.fullname, COUNT(rm.id) as count, $multiplier_subquery, u.username
        FROM rpt_messages rm
        LEFT JOIN tbl_services s ON s.id = rm.service_id
        LEFT JOIN tbl_users u ON u.id = rm.user_id
        LEFT JOIN tbl_roles r ON u.role_id = r.id
        WHERE rm.bound_time BETWEEN '$start_date' AND '$end_date'
        AND rm.direction = 'outbound'
        GROUP BY rm.user_id";
    
 
      $reward_subquery ="SELECT fm.operator_id, COUNT(fm.id) AS rewards
        FROM tbl_flagged_messages fm
        WHERE fm.date_flagged BETWEEN '$start_date' AND '$end_date'
        AND fm.status = 'approved' 
        GROUP BY fm.operator_id";

       $deductions_subquery = "SELECT m.user_id as operator_id, COUNT(fm.id) AS deductions
        FROM tbl_flagged_messages fm
        LEFT JOIN tbl_messages m ON fm.message_id = m.id
        WHERE fm.date_flagged BETWEEN '$start_date' AND '$end_date'
        AND fm.status = 'approved' 
        GROUP BY m.user_id";

       $results['payout'] = \DB::select(DB::raw("SELECT * FROM ($basequery) AS po_1
      LEFT JOIN ($reward_subquery) AS po_3 ON po_1.rm_user_id = po_3.operator_id 
      LEFT JOIN ($deductions_subquery) AS po_4 ON po_1.rm_user_id = po_4.operator_id"));
        
    
        $func = function($row){
           $data["operator"] = $row->fullname;
           $data["stats"] = $row->count;
            return $data;
        };

      $data["data"] = array_map($func,$results["payout"]);      
      $data['platform_code'] = \Config::get('api.platform_code_prody');
      $data["from_datetime"] = $start_date;      
      $data["to_datetime"] = $end_date;   
       
       $request_formbody = json_encode($data); 
       $response = $this->curl_post(Config::get('api.production_reports_url'),array("data" => $request_formbody));

       $log = new \TblProductionApiLogs();
      $log->request = $request_formbody;
      $log->response = $response;
      $log->report_date = $current_time;
      $log->date_created = gmdate("Y-m-d H:i:s");
      $log->save();
      echo $response;
      }
}