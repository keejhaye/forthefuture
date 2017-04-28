<?php
namespace App\Libraries;
use Mail;

class Mailsender {

 
  private static $dataContext = '';

  public static function send_mail($viewData, $sendData){
      static::$dataContext = $sendData;
      Mail::send(static::$dataContext['viewForm'], ['viewData' => $viewData], function($message)
      {
          $message->from(static::$dataContext['emailFrom'] , static::$dataContext['fromDesc']);
          $message->to(static::$dataContext['emailTo'], static::$dataContext['toDesc'])->subject(static::$dataContext['mailSubj']);
      });
  }
  









}