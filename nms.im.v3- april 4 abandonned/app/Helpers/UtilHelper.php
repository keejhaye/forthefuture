<?php

namespace App\Helpers;

class UtilHelper {
     static function sanitize($data, $level = 1) {

    //remove spaces from the input
    $data = trim($data);

    //convert special characters to html entities
    if ($level > 0) {
      $data = str_replace("<br>", "\n", $data);
      $data = str_replace("<br />", "\n", $data);
      $data = str_replace("<br/>", "\n", $data);
      $data = strip_tags($data, "<img><a>");
      $data = htmlspecialchars($data);
      $data = nl2br($data);
    }

    // Remove all unwanted chars. Keep only the ones listed
    if ($level > 1)
      $data = preg_replace('/[^A-Za-z0-9]/is', '', $data);

    //sanitize before using any MySQL database queries
    if ($level > 2)
      $data = mysql_real_escape_string($data);

    return $data;
  }

  static function clean_text($text) {
    $sanitized = strip_tags($text);
    $sanitized = htmlentities($text);

    return $sanitized;
  }

    static function send_curl($url, $data) {
    // is cURL installed yet?
    if (!function_exists('curl_init')) {
      die('Sorry cURL is not installed!');
    }

    // OK cool - then let's create a new cURL resource handle
    $ch = curl_init();

    // Now set some options (most are optional)
    // Set URL to download
    curl_setopt($ch, CURLOPT_URL, $url);

    // Set a referer
   // curl_setopt($ch, CURLOPT_REFERER, $url);

    // User agent
    curl_setopt($ch, CURLOPT_USERAGENT, "MozillaXYZ/1.0");

    // Include header in result? (0 = yes, 1 = no)
    curl_setopt($ch, CURLOPT_HEADER, 0);

    // Should cURL return or print out the data? (true = return, false = print)
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Timeout in seconds
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);

   // trace::add_trace("sending: " . $data);

    // set post for this example
    // parse post sent to IMV2
    $post_array = 'data=' . urlencode($data);

    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_array);
    curl_setopt($ch, CURLOPT_POST, 1);
    // end post
    // Download the given URL, and return output
    $response = curl_exec($ch);

    // Close the cURL resource, and free system resources
    curl_close($ch);

    return $response;
  }
}

