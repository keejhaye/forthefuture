<?php
namespace App\Libraries;

class Spamfilter {

  public static function filter($data){
     $regexp = "\b\w*[\.]\w*\b|(\.|dot)(com|net|org)|\b(www|https?)\.?\b";
    if(trim($data) != ""){
     preg_match_all("/{$regexp}/", $data, $matches);      
      $filtered = array();
      if(!empty($matches[0])){
        foreach($matches[0] as $key => $value){
          if(!is_numeric($value))
            $filtered[] = $value;
        }         
      } 
    }
    return $filtered;
  }
  









}