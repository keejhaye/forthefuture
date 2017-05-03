<?php

namespace App\Http\Controllers\Panel;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
header('Cache-Control: no-cache');
header('Pragma: no-cache');

class Reports extends Controller {
  public function index() {
    return view('panel/ReportsContent');
  }

  public function filter($entity){
    $entity_arr = array('service', 'persona', 'subcriber', 'user');
    $results = array();

    if(in_array($entity, $entity_arr) && isset($_GET['search'])){
      $query = [];
      $search = $_GET['search'];
      $params = array(
        'keyword' => $search,
        'limit' => config('application.reports_autocomplete_limit'));

      switch ($entity) {
        case 'service': $query = \TblServices::search($params)->get(); break;
        case 'persona': $query = \TblPersonas::search($params)->get(); break;
        case 'subscriber': $query = \TblSusbcribers::search($params)->get(); break;
        case 'user': $query = \TblUsers::search_user($params)->get(); break;
      }

      // die("<pre>" . print_r($query,1));

      if(count($query) > 0)
        $results["results"] = $this->format_for_autocomplete($query, $entity);
   
    }

    echo json_encode($results);
  }

  protected function format_for_autocomplete($results, $entity){
    //return only id and name
    $selection = [];

    foreach ($results as $row) {
      $item = array('id' => $row->id);

      switch ($entity) {
        case 'user': $item['name'] = $row->firstname . " " .$row->lastname . " (" . $row->username . ")"; break;
        case 'service':
        case 'persona':
        case 'subscriber':
          $item['name'] = $row->name;
          break;
      }

      array_push($selection, $item); 
    }

    return $selection;
  }

}
