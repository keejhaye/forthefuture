<?php
  
try {
  $info = json_decode($_POST["data"], true);
  $post = json_encode($info["data"]);
  $post_array = 'data=' . urlencode($post);  

  $ch = curl_init();  
  curl_setopt($ch, CURLOPT_URL, $info["url"]);  
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_TIMEOUT, 10);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $post_array);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_SSLVERSION, 3);
  $output = curl_exec($ch);


    if (FALSE === $output)
        throw new Exception(curl_error($ch), curl_errno($ch));

  die(var_dump($output));
  curl_close($ch);
} catch(Exception $e) {

    trigger_error(sprintf(
        'Curl failed with error #%d: %s',
        $e->getCode(), $e->getMessage()),
        E_USER_ERROR);
}  
?>