<?php
include_once("./lib/resize-class.php");

//variables config
$resize_to_width = "190";
$resize_to_height = "130";
$srcDir = "/users/mygoare/Pictures/";
$distDir = dirname(__FILE__)."/dist/";
if (!is_dir($distDir)) {mkdir($distDir);}

$files_arr = array();

recursionDir($srcDir);
function recursionDir($dir){
  // http://stackoverflow.com/questions/5060465/get-variables-from-the-outside-inside-a-function-in-php
  global $files_arr;
  if(is_dir($dir)) {
    if($dp = opendir($dir)) {
      while ( ($file = readdir($dp)) !== false ) {
        if ($file != "." && $file != "..") {
          if (is_dir($dir.$file)) {
            //递归
            recursionDir($dir.$file."/");
          } elseif (filesize($dir.$file) > 11 && checkImageExtension($dir.$file)) {
            //key is 路径, value is filename.
            $files_arr[$dir.$file] = $file;
          }
        }
      }
      closedir($dp);
    }
  }
}

function checkImageExtension($filePath) {
  $ex_arr = ['.jpg', '.gif', '.png'];
  $ex = strtolower(strrchr($filePath, '.'));
  if ($ex == ".jpeg") {
    $ex = ".jpg";
  }

  //后缀名 与 实际相符
  if ($ex && in_array($ex, $ex_arr) && $ex == recheckImageExtension($filePath)) {
    return true;
  } else {
    return false;
  }
}

function recheckImageExtension($filePath) {
  $extension = "";
  $image_type_arr = getImageSize($filePath);
  switch ($image_type_arr["mime"]) {
    case "image/gif":
      $extension = ".gif";
      break;
    case "image/jpeg":
      $extension = ".jpg";
      break;
    case "image/png":
      $extension = ".png";
      break;
    default:
      break;
  }
  return $extension;
}

function debug() {
var_dump ($files_arr);
return false;
}

if (!empty($files_arr)) {
  foreach ($files_arr as $key => $value) {
    $resizeObj = new resize($key);
    $resizeObj->resizeImage($resize_to_width, $resize_to_height, 'crop');
    //save image to distDir
    $resizeObj->saveImage($distDir.$value);
  }
}
?>
