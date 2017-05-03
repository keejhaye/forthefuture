<?php
namespace App\Libraries;
namespace App\Libraries\Piczelle;
/**
 * Piczelle is a photo algorithm that takes, manipulates then displays a photo
 * given a set of parameters.
 *
 * @package    Piczelle
 * @author     Razelle Cuevas <zellecuevas@gmail.com>
 */
class Piczelle{
  protected $height;
  protected $width;
  protected $crop;
  protected $colorfill;
  protected $fallback_image;
  
  /**
   * @var string [ text | image ]
   */
  protected $error_mode;

  function __construct(){
    $this->height = 100;
    $this->width = 100;
    $this->crop = 'fill'; //or slice, thumb
    $this->colorfill = 'FFFFFF';
    
    // set this URL if you want a fallback image to be shown instead of error messages
    $this->fallback_image = url("img/no-image.png");
    $this->error_mode = "image";
  }

  protected function show_message($type, $message){
    if($this->error_mode == "text"){
      die("[{$type}] {$message}");
    }else if($this->error_mode == "image"){
      $this->manipulate("fill", $this->fallback_image, "FFFFFF", $this->width, $this->height);
    }
  }
  
  /**
   * Crop the image according to crop type
   * @param string $crop        crop type: slice, thumb, fill
   * @param string $url         image path
   * @param string $colorfill   background color in hex
   * @param int $width          width of output image
   * @param int $height         height of output image
   */
  public function manipulate($crop, $url, $colorfill, $width, $height){
    $this->crop = $crop;
    $this->colorfill = $colorfill;
    $this->width = $width;
    $this->height = $height;
    
    //check if valid image file
    $array = $this->validate_filetype($url);
    if($array["type"] != false){
        // validate url
        if($this->verify_url($url)){
          $img_info = getimagesize($array["url"]);

          $this->memory_regulator($width, $height, $array["url"]);

          try{
            // display headers...
            $seconds_to_cache = 2592000; // 30 days to keep in cache
            $ts = gmdate("D, d M Y H:i:s", time() + $seconds_to_cache) . " GMT";
            ob_clean();
            header("Expires: $ts");
            header("Pragma: no-cache");
            header("Cache-Control: max-age=$seconds_to_cache");

            // display the cropped image...
            $this->crop($crop, $url, $img_info, $colorfill, $width, $height);
          }catch(Exception $e){
            $this->show_message ("error", "unable to render image: ".$e);
          }
        }
        else{
          $file_headers = @get_headers($url);
          if($file_headers == false){
            $this->show_message("image", "");
          }
          else{
            $this->show_message ("error", "unable to load url: {$url}");
          }
        }
    }
    else
      $this->show_message ("error", "not a valid file. supported: JPG, GIF, PNG");
  }

  /*FUNCTIONS*/

  /**
   * Checks crop type defined by user is applicable, if not, use default
   * @param int $img_width    width of the input image
   * @param int $img_height   height of the input image
   * @param int $res_width    width of the output image
   * @param int $res_height   height of the output image
   * @param string $crop         crop type: fill, slice or thumb
   **/
  protected function crop_type($img_width,$img_height, $res_width, $res_height, $crop){
      if(($res_height > $img_height || $res_width > $img_width) && $crop != 'thumb')
          $type = 'fill';
      else $type = $crop;
     
      return $type;
  }
  
  /**
   * Rejects image that is too large to render
   * Function created out of need to control memory exhaustion produced by imagecreatefromjpeg()
   * This function is a quick fix only.
   * Must replace rendering of images with Imagick library
   * @param int $width width of image
   * @param int $height height of image
   * @param string $url original location of the image resource
   */
  protected function memory_regulator($width, $height, $url)
  {
    /** start quick fix**/

      $mem_limit = ini_get('memory_limit');
      $new_str = preg_replace('/M$/', '', $mem_limit);
      $final_memory_limit = ((int)$new_str * 1048576) * 1.7;

      list ($width, $height) = getimagesize($url);
      $image_memory = $width * $height * 4; // 4 bytes per pixel

      if($final_memory_limit < $image_memory)
      {
        $this->show_message ('error', 'Memory too large to render this image. Please use a smaller picture to render this image.');
      }
      
      return;
    /** end quick fix**/
  }

  /**
   * @param string $url path to the image file
   **/
  protected function validate_filetype($url){      
      $type_list = array('gif', 'png', 'jpg', 'jpeg');
      $new_url = '';
      $m = explode(".", $url);

      $type = $m[count($m)-1];

      $temp = strtolower($type);
      
      for($i=0; $i< count($m)-1; $i++){
          $new_url .= $m[$i];
      }
      $new_url .= ".".$temp;
      
      if(in_array($temp, $type_list))
          return array("type" => $temp, "url"=> $url);
      else return FALSE;
  }
  
  /**
   * verify if this url exists
   * @param type $url
   * @return boolean 
   */
  protected function verify_url($url){
    $file_headers = @get_headers($url);
    if($file_headers[0] == 'HTTP/1.1 404 Not Found' || $file_headers == false) {
        $exists = false;
    }
    else {
        $exists = true;
    }
    
    return $exists;
  }

  /**
   * Crop the image according to crop type
   * @param string $crop      crop type: slice, thumb, fill
   * @param string $url       image path
   * @param array $img_info   image information generated from getimagesize()
   * @param string $colorfill background color in hex
   * @param int $res_width    width of output image
   * @param int $res_height   height of output image
   */
  protected function crop($crop, $url, $img_info, $colorfill, $res_width, $res_height){
      list($img_width, $img_height, $type, $attr) = $img_info;

      $crop = $this->crop_type($img_width,$img_height,$res_width, $res_height, $crop);
      
      if($crop == 'slice' ){
          $this->crop_slice($url,$img_width,$img_height,$res_width, $res_height);
      }

      else if($crop == 'thumb'){
          $this->crop_thumb($url, $img_width, $img_height, $res_width, $res_height);
      }

      else{
          $this->crop_fill($url, $img_width, $img_height, $res_width, $res_height, $colorfill);
      }
  }

  /**
   * Slice the middle part of the image.
   * Applicable for images bigger than the output image
   * @param string $img       image path
   * @param int $img_width    width of input image
   * @param int $img_height   height of input image
   * @param int $res_width    width of output image
   * @param int $res_height   height of output image
   */
  protected function crop_slice($img,$img_width,$img_height,$res_width,$res_height){
      $array = $this->validate_filetype($img); 
      $src = $this->imagecreate_by_filetype($array["type"],$array['url']);
      $dest = imagecreatetruecolor($res_width, $res_height);

      $width_diff = $img_width - $res_width;
      $height_diff = $img_height - $res_height;

      $x_offset = $width_diff/2;
      $y_offset = $height_diff/2;

      imagecopy($dest, $src, 0, 0, $x_offset, $y_offset, $img_width, $img_height);

      header('Content-Type: image/gif');
      $this->output_image_by_type($array["type"], $dest);

      imagedestroy($dest);
      imagedestroy($src);
  }

  /**
   * @param string $type  image type: gif, jpg or png
   * @param string $img   image path
   * @return resource
   */

  protected function imagecreate_by_filetype($type, $img){ //$img is a string(path)
      switch($type){
          case 'gif':
              $src = imagecreatefromgif($img);
              break;
          case 'png':
              $src = imagecreatefrompng($img);
              break;
          case 'jpg':
              $src = imagecreatefromjpeg($img);
              break;
          case 'jpeg':
              $src = imagecreatefromjpeg($img);
              break;
      }
      return $src;
  }

  /**
   * @param type $type   image type
   * @param type $image  image resource
   * @return bool
   */
  protected function output_image_by_type($type, $image){ //$image is a resource
      switch($type){
          case 'gif':
              $src = imagegif($image);
              break;
          case 'png':
              $src = imagepng($image);
              break;
          case 'jpg':
              $src = imagejpeg($image);
              break;
          case 'jpeg':
              $src = imagejpeg($image);
              break;
      }

      return $src;
  }

  /**
   * @param string $img       image path
   * @param int $img_width    width of input image
   * @param int $img_height   height of input image
   * @param int $res_width    width of output image
   * @param int $res_height   height of output image
   * @param string $colorfill background color
   */
  protected function crop_fill($img,$img_width,$img_height,$res_width,$res_height,$colorfill){
//    echo "[$img,$img_width,$img_height,$res_width,$res_height,$colorfill]";
      $array = $this->validate_filetype($img);
      
      $source = $this->imagecreate_by_filetype($array["type"], $array["url"]);
      $scale      = min($res_width/$img_width, $res_height/$img_height);

      $new_width  = floor($scale*$img_width);
      $new_height = floor($scale*$img_height);
      
      $thumb = imagecreatetruecolor($new_width, $new_height);
      imagecopyresampled($thumb, $source,0, 0, 0, 0,$new_width, $new_height, $img_width, $img_height);


      $width_diff = $res_width - $new_width;
      $height_diff = $res_height - $new_height;

      $x_offset = $width_diff/2;
      $y_offset = $height_diff/2;

      $thumb2 = imagecreatetruecolor($res_width, $res_height);
      $rgb = $this->hex_to_rgb($colorfill);
      $color = imagecolorallocate($thumb2, $rgb['r'], $rgb['g'], $rgb['b']);

      if($width_diff != 0 || $height_diff != 0){
        imagefill($thumb2, 0, 0, $color);
      }

      imagecopy($thumb2, $thumb, $x_offset, $y_offset, 0, 0, $new_width, $new_height);  
      
      header('Content-Type: image/gif');
      $this->output_image_by_type($array["type"], $thumb2);
  }

  /**
   * @param string $img       image path
   * @param int $img_width    width of input image
   * @param int $img_height   height of input image
   * @param int $res_width    width of output image
   * @param int $res_height   height of output image
   */
  protected function crop_thumb($img,$img_width,$img_height, $res_width, $res_height){
      $array = $this->validate_filetype($img);
      
      $source = $this->imagecreate_by_filetype($array["type"], $array["url"]);
      
      //portrait
      if($img_height > $img_width){
          $x_offset = 0;
          $y_offset = ($img_height - $img_width)/2;
          $size = $img_width;
      }
      //landscape
      else{
          $x_offset = ($img_width - $img_height)/2;
          $y_offset = 0;
          $size = $img_height;
      }

      $des = imagecreatetruecolor($res_width, $res_height);
      imagecopyresampled($des, $source, 0, 0, $x_offset, $y_offset,$res_width, $res_height, $size, $size);

      header('Content-Type: image/gif');
      $this->output_image_by_type($array["type"], $des);
  }

  /**
   * Convert hex to rgb
   * @param string $hex   hex value
   * @return array
   */

  protected function hex_to_rgb($hex){
      if (substr($hex,0,1) == "#")
          $hex = substr($hex,1);

      $r = substr($hex,0,2);
      $g = substr($hex,2,2);
      $b = substr($hex,4,2);

      $r = hexdec($r);
      $g = hexdec($g);
      $b = hexdec($b);

      $rgb['r'] = $r;
      $rgb['g'] = $g;
      $rgb['b'] = $b;

      return $rgb;
  }
}