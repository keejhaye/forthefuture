<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Libraries;

class Image extends Controller {
  public function index(){
    $pic = new \Piczelle();
    
    $pic->manipulate( 
            $this->input->get("type", "thumb")
            , $this->input->get("url")
            , $this->input->get("fill", "ffffff")
            , $this->input->get("width", "200")
            , $this->input->get("height", "200"));
  }

  public function thumb(Request $request){
    header("Content-Type: image");
    $pic = new \Piczelle();

    $response = $pic->manipulate(
      $request->input("type", "thumb")
      , $request->input("url")
      , $request->input("fill", "000000")
      , $request->input("width", "200")
      , $request->input("height", "200"));

    return response($response)
            ->header("Content-Type", "image");
  }

}