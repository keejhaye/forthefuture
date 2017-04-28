<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;

class Message extends Controller {

    public function index(){        
       $data = array(
                "username" => "0",
                "password" => "0",
                "service_code" => "50d6306840",
                "service_name" => "CL-WLHLO IM Live",
                "from" => "sexystephanie94",
                "to" => "ellen_kon49061",
                "message" => "test",
                "attachment" => "",
                "additional_info" => array(
                    "billed" => true,
                    "conversation_id" => 3776035,
                    "operatorId" => "RKFL2556"
                    )
            ); 

        $response = self::curl_download('http://phantom.wellhello.com/api/livechat/sendIM', $data);
        die($response);
    }

    public function duration(){
        $cd = \TblConversationDuration::where('conversation_id', 23)
               // ->whereIn('status', 'ongoing-in') 
                // ->sortByDesc('id')
                // ->limit(1)
                ->get()
                ->last();

        die("<pre>".var_dump($cd->toArray()));
    }


    protected function curl_download($url, $data){
    // is cURL installed yet?
    if (!function_exists('curl_init')){
        die('Sorry cURL is not installed!');
    }

    // OK cool - then let's create a new cURL resource handle
    $ch = curl_init();

    // Now set some options (most are optional)

    // Set URL to download
    curl_setopt($ch, CURLOPT_URL, $url);

    // Set a referer
    curl_setopt($ch, CURLOPT_REFERER, "http://www.example.org/yay.htm");

    
    // User agent
    curl_setopt($ch, CURLOPT_USERAGENT, "MozillaXYZ/1.0");

    // Include header in result? (0 = yes, 1 = no)
    curl_setopt($ch, CURLOPT_HEADER, 0);

    // Should cURL return or print out the data? (true = return, false = print)
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Timeout in seconds
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);

     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        // set data arr
        $post_data = json_encode($data);
        // set post for this example
        $post_array = 'data='.urlencode($post_data);


        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_array);
        curl_setopt($ch, CURLOPT_POST, 1);  
        // end post 
    // Download the given URL, and return output
    $output = curl_exec($ch);

    // Close the cURL resource, and free system resources
    curl_close($ch);
    return $output;
  }
}
